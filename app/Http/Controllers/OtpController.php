<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OtpController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show OTP login form
     */
    public function showOtpLoginForm(Request $request)
    {
        $role = $request->get('role', 'user'); // Default to user
        
        if (!in_array($role, ['user', 'vendor'])) {
            abort(404);
        }
        
        return view('auth.otp-login', compact('role'));
    }

    /**
     * Send OTP to mobile number
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
            'role' => 'required|in:user,vendor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid 10-digit mobile number.',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->phone;
        $role = $request->role;

        // Check if user exists with this phone and role
        $user = User::where('phone', $phone)->where('role', $role)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "No {$role} account found with this mobile number."
            ], 404);
        }

        // Check if vendor is approved (for vendor role)
        if ($role === 'vendor' && $user->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Your vendor account is pending approval.'
            ], 403);
        }

        // Generate OTP
        $otp = $this->smsService->generateOtp();
        $expiresAt = Carbon::now()->addMinutes(5);

        // Save OTP to user
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => $expiresAt
        ]);

        // Send OTP via SMS
        $smsResult = $this->smsService->sendOtp($phone, $otp);

        if ($smsResult['success']) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your mobile number.',
                'expires_in' => 300 // 5 minutes in seconds
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
                'error' => $smsResult['message']
            ], 500);
        }
    }

    /**
     * Verify OTP and login user
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
            'otp' => 'required|numeric|digits:6',
            'role' => 'required|in:user,vendor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter valid phone number and OTP.',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->phone;
        $otp = $request->otp;
        $role = $request->role;

        // Find user with phone and role
        $user = User::where('phone', $phone)->where('role', $role)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "No {$role} account found with this mobile number."
            ], 404);
        }

        // Check if OTP exists and is not expired
        if (!$user->otp_code || !$user->otp_expires_at) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found. Please request a new OTP.'
            ], 400);
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new OTP.'
            ], 400);
        }

        // Verify OTP
        if ($user->otp_code !== $otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 400);
        }

        // OTP is valid, clear it and mark phone as verified
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'phone_verified' => true,
            'phone_verified_at' => Carbon::now()
        ]);

        // Login the user
        Auth::login($user);
        $request->session()->regenerate();

        // Determine redirect URL based on role
        $redirectUrl = $role === 'vendor' ? route('vendor.dashboard') : route('user.dashboard');

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'redirect_url' => $redirectUrl
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
            'role' => 'required|in:user,vendor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid 10-digit mobile number.',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->phone;
        $role = $request->role;

        // Check if user exists
        $user = User::where('phone', $phone)->where('role', $role)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "No {$role} account found with this mobile number."
            ], 404);
        }

        // Check rate limiting (prevent spam)
        if ($user->otp_expires_at && Carbon::now()->isBefore($user->otp_expires_at->subMinutes(4))) {
            $waitTime = $user->otp_expires_at->subMinutes(4)->diffInSeconds(Carbon::now());
            return response()->json([
                'success' => false,
                'message' => "Please wait {$waitTime} seconds before requesting a new OTP."
            ], 429);
        }

        // Generate new OTP
        $otp = $this->smsService->generateOtp();
        $expiresAt = Carbon::now()->addMinutes(5);

        // Save new OTP
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => $expiresAt
        ]);

        // Send OTP via SMS
        $smsResult = $this->smsService->sendOtp($phone, $otp);

        if ($smsResult['success']) {
            return response()->json([
                'success' => true,
                'message' => 'New OTP sent successfully.',
                'expires_in' => 300
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
                'error' => $smsResult['message']
            ], 500);
        }
    }
}
