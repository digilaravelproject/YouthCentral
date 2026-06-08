<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\YcIgnite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class YcIgniteController extends Controller
{
    // public function store(Request $request)
    // {
    //     //dd($request->all());
    //     $validated = $request->validate([
    //         'event_id' => 'required|exists:events,id',
    //         'first_name' => 'required|string|max:191',
    //         'middle_name' => 'nullable|string|max:191',
    //         'last_name' => 'required|string|max:191',
    //         'email' => 'required|email',
    //         'mobile' => 'required|string|max:20',
    //         'dob' => 'required|date',
    //         'age_category' => 'required|integer',
    //         'address' => 'required|string',
    //         'school' => 'required|string|max:191',
    //         'grade' => 'required|string|max:50',
    //         'sport_event' => 'required|string|max:191',
    //         'parent_consent'   => 'accepted',
    //         'terms_conditions' => 'accepted',
    //     ]);

    //     $validated['user_id'] = Auth::id();
    //     $validated['parent_consent']   = $request->boolean('parent_consent');
    //     $validated['terms_conditions'] = $request->boolean('terms_conditions');

    //     $ycIgnite = YcIgnite::create($validated);

    //     Mail::send('emails.yc_ignite_receipt', ['data' => $ycIgnite], function ($message) use ($ycIgnite) {
    //         $message->to($ycIgnite->email, $ycIgnite->first_name . ' ' . $ycIgnite->last_name)
    //             ->subject('YC Ignite 2025 - Registration Receipt');
    //     });


    //     return redirect()->back()->with('success', 'Registration submitted successfully! A receipt has been sent to your email.');
    // }


    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'first_name' => 'required|string|max:191',
            'middle_name' => 'nullable|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email',
            'mobile' => 'required|string|max:20',
            'dob' => 'required|date',
            'age_category' => 'required|integer',
            'address' => 'required|string',
            'school' => 'required|string|max:191',
            'grade' => 'required|string|max:50',
            'sport_event' => 'required|string|max:191',
            'parent_consent'   => 'accepted',
            'terms_conditions' => 'accepted',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['parent_consent'] = $request->boolean('parent_consent');
        $validated['terms_conditions'] = $request->boolean('terms_conditions');

        // ---------- Generate OTP ----------
        $otp = rand(100000, 999999);

        // Format mobile for SMS provider
        $mobileDigits = preg_replace('/\D+/', '', $request->mobile);
        $smsMobile = strlen($mobileDigits) === 10 ? '91' . $mobileDigits : $mobileDigits;

        // Store registration data + OTP temporarily in session
        $storedData = $request->only([
            'event_id',
            'first_name',
            'middle_name',
            'last_name',
            'dob',
            'age_category',
            'address',
            'email',
            'mobile',
            'school',
            'grade',
            'sport_event',
        ]);

        $storedData['parent_consent']   = $validated['parent_consent'];
        $storedData['terms_conditions'] = $validated['terms_conditions'];

        session([
            'yc_ignite_registration_data' => $storedData,
            'otp' => $otp,
            'otp_mobile' => $smsMobile,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // ---------- Send OTP ----------
        try {
            $this->sendOtp($smsMobile, $otp);
            Log::info('YC Ignite OTP sent', ['mobile' => $smsMobile, 'otp' => $otp]);
        } catch (\Exception $e) {
            Log::error('YC Ignite OTP sending failed', [
                'mobile' => $smsMobile,
                'error' => $e->getMessage()
            ]);

            session()->forget(['yc_ignite_registration_data', 'otp', 'otp_mobile', 'otp_expires_at']);

            return redirect()->back()->with('error', 'Failed to send OTP. Please try again later.');
        }

        // Redirect to OTP verification page
        return redirect()->route('yc_ignite.verifyOtp')
            ->with('info', 'OTP sent to your mobile. Please verify to complete registration.');
    }

    /**
     * Send OTP (DLT-compliant template)
     */
    private function sendOtp(string $mobileWithCountryCode, string|int $otp)
    {
        $apiKey   = config('services.msgclub.auth_key');
        $senderId = config('services.msgclub.sender_id');

        // DLT-compliant message template
        $message = "Dear user, Welcome to YOUTHCENTUARY! To complete your registration for YC SPARK 2025, please enter OTP: $otp. Do not share this OTP to anyone for security reasons.";

        $client = new \GuzzleHttp\Client(['timeout' => 10]);

        $response = $client->request('GET', 'https://msg.msgclub.net/rest/services/sendSMS/sendGroupSms', [
            'query' => [
                'AUTH_KEY' => $apiKey,
                'message' => $message,
                'senderId' => $senderId,
                'routeId' => '8',
                'mobileNos' => $mobileWithCountryCode,
                'smsContentType' => 'english',
            ],
        ]);

        $body = (string) $response->getBody();
        Log::debug('MsgClub response', ['body' => $body]);

        if ($response->getStatusCode() !== 200 || !str_contains($body, '"responseCode":"3001"')) {
            throw new \Exception('MsgClub failed: ' . $body);
        }
    }


    public function verifyOtpSubmit(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionData = session('yc_ignite_registration_data');
        $otp         = session('otp');
        $otpExpires  = session('otp_expires_at');

        if (!$sessionData || !$otp || now()->greaterThan($otpExpires)) {
            //return redirect()->route('yc-ignite.store')->with('error', 'OTP expired. Please try again.');
            return redirect()
                ->route('yc_ignite.verifyOtp')
                ->with('error', 'OTP expired. Please try again.');
        }

        if ($request->otp != $otp) {
            //return redirect()->back()->with('error', 'Invalid OTP.');
            return redirect()
                ->route('yc_ignite.verifyOtp')
                ->with('error', 'Invalid OTP.');
        }

        // OTP verified → save registration
        $sessionData['user_id'] = Auth::id();
        $ycIgnite = YcIgnite::create($sessionData);

        // Send receipt email
        Mail::send('emails.yc_ignite_receipt', ['data' => $ycIgnite], function ($message) use ($ycIgnite) {
            $message->to($ycIgnite->email, $ycIgnite->first_name . ' ' . $ycIgnite->last_name)
                ->subject('YC Ignite 2025 - Registration Receipt');
        });

        // Clear session
        session()->forget(['yc_ignite_registration_data', 'otp', 'otp_mobile', 'otp_expires_at']);

        return redirect()
            ->route('events.yc_ignite', $ycIgnite->event)
            ->with('success', 'Registration completed successfully! A receipt has been sent to your email.');
    }



    public function myRegistrations()
    {
        // $registrations = YcIgnite::where('user_id', Auth::id())
        //     ->with('event')
        //     ->latest()
        //     ->get();

        $registrations = YcIgnite::with(['user', 'event'])
            ->latest()
            ->paginate(10);

        return view('user.events.yc_ignites.index', compact('registrations'));
    }
}
