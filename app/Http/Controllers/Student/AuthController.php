<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\EventRegistration;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
        $this->middleware('guest:student')->except('logout');
    }

    /**
     * Show the student login form.
     */
    public function showLoginForm()
    {
        return view('student.auth.login');
    }

    /**
     * Handle student mobile verification and send OTP.
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone = $request->phone;

        // Check if phone number exists in event_registrations
        $registration = EventRegistration::where('mobile', $phone)->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'Mobile number is not registered for any events.')->withInput();
        }

        // Check rate limiting (prevent spam)
        $student = Student::where('phone', $phone)->first();
        if ($student && $student->otp_expires_at) {
            // otp_expires_at was set to the time the OTP expires (sent_at + 5 minutes)
            // derive the original sent time and allow a new OTP after 1 minute
            $sentAt = Carbon::parse($student->otp_expires_at)->subMinutes(5);
            $allowedAt = $sentAt->copy()->addMinute();
            if (Carbon::now()->lt($allowedAt)) {
                $waitTime = Carbon::now()->diffInSeconds($allowedAt);
                return redirect()->back()->with('error', "Please wait {$waitTime} seconds before requesting a new OTP.");
            }
        }

        // Generate OTP
        $otp = $this->smsService->generateOtp();
        $expiresAt = Carbon::now()->addMinutes(5);

        // Create or update Student record
        if (!$student) {
            $student = Student::create([
                'phone' => $phone,
                'name' => trim($registration->first_name . ' ' . $registration->last_name),
                'otp_code' => $otp,
                'otp_expires_at' => $expiresAt,
                'student_class' => $registration->grade,
                'event_registration_id' => $registration->id,
            ]);
        } else {
            $student->update([
                'otp_code' => $otp,
                'otp_expires_at' => $expiresAt,
                'name' => trim($registration->first_name . ' ' . $registration->last_name),
                'student_class' => $registration->grade,
                'event_registration_id' => $registration->id,
            ]);
        }

        // Send OTP via SMS
        $smsResult = $this->smsService->sendOtp($phone, $otp);

        if ($smsResult['success']) {
            // Store phone in session for OTP page
            $request->session()->put('student_auth_phone', $phone);
            return redirect()->route('student.otp')->with('success', 'OTP sent successfully to your mobile number.');
        } else {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.')->withInput();
        }
    }

    /**
     * Show OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('student_auth_phone')) {
            return redirect()->route('student.login');
        }

        return view('student.auth.otp');
    }

    /**
     * Verify OTP and login student.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $phone = $request->session()->get('student_auth_phone');
        
        if (!$phone) {
            return redirect()->route('student.login')->with('error', 'Session expired. Please try again.');
        }

        $student = Student::where('phone', $phone)->first();

        if (!$student) {
            return redirect()->route('student.login')->with('error', 'Account not found.');
        }

        // Check if OTP exists and is not expired
        if (!$student->otp_code || !$student->otp_expires_at) {
            return redirect()->back()->with('error', 'No OTP found. Please request a new OTP.');
        }

        if (Carbon::now()->isAfter($student->otp_expires_at)) {
            return redirect()->back()->with('error', 'OTP has expired. Please request a new OTP.');
        }

        // Verify OTP
        if ($student->otp_code !== $request->otp) {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }

        // OTP is valid, clear it
        $student->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Login the student
        Auth::guard('student')->login($student);

        // Increment login streak
        app(\App\Services\StudentProgressService::class)->incrementLoginStreak($student);
        
        $request->session()->forget('student_auth_phone');
        $request->session()->regenerate();

        return redirect()->route('student.dashboard');
    }

    /**
     * Log out student.
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
