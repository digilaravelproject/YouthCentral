<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\EventRegistrationSuccess;
use App\Mail\AdminNotification;
use App\Mail\UserRegistered;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PDF;

class EventRegistrationController extends Controller
{
    protected $razorpayService;
    
    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }
    
    public function registerForm(Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }

        // Prevent access to registration form for general events
        if ($event->category === 'general') {
            return redirect()->route('events.show', $event)
                ->with('error', 'Registration is not available for general events.');
        }

        if ($event->seat_limit && $event->registrations()->count() >= $event->seat_limit) {
            return back()->with('error', 'This event has reached its maximum capacity.');
        }

        return view('user.events.register', compact('event'));
    }

    public function processRegistration_old(Request $request, Event $event)
    {
        // Prevent registration for general events
        // if ($event->category === 'general') {
        //     return redirect()->route('events.show', $event)
        //         ->with('error', 'Registration is not available for general events.');
        // }

        Log::info('Processing Event Registration', [
            'event_id' => $event->id,
            'category'  => $event->category,
            'user_id'   => auth()->id(),
            'is_auth'   => auth()->check(),
            'request_data' => $request->except(['_token'])
        ]);

        $request->validate([
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'dob'          => 'required|date|before:today',
            'address'      => 'required|string',
            'email'        => 'required|email|max:255',
            'mobile'       => 'required|string|max:20',
            'school'       => 'required|string|max:255',
            'grade'        => 'required|string|max:50',
            'sport_event'  => 'required|string|max:255',
            'age_category' => 'required|string|max:50',
        ]);

        // ---------- IMPORTANT: Run seat-limit + duplicate checks BEFORE sending OTP ----------
        // Seat limit
        if ($event->seat_limit && $event->registrations()->where('payment_status', 'completed')->count() >= $event->seat_limit) {
            return redirect()->route('events.show', $event)->with('error', 'Sorry, this event is now full.');
        }

        // Duplicate / pending checks (same logic as before)
        $paidStatuses = ['paid', 'completed'];
        if (auth()->check()) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->whereIn('payment_status', $paidStatuses)
                ->first();
            if ($existingRegistration) {
                return redirect()->route('events.show', $event)
                    ->with('info', 'You are already registered for this event.');
            }

            // for paid events, check for pending registration
            if ($event->registration_amount > 0) {
                $pendingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', auth()->id())
                    ->where('payment_status', 'pending')
                    ->first();
                if ($pendingRegistration) {
                    return redirect()->route('events.payment.show', $pendingRegistration)
                        ->with('info', 'You have a pending registration. Please complete payment.');
                }
            }
        } else {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('email', $request->email)
                ->whereIn('payment_status', $paidStatuses)
                ->first();
            if ($existingRegistration) {
                return redirect()->route('events.show', $event)
                    ->with('info', 'This email is already registered for this event.');
            }

            if ($event->registration_amount > 0) {
                $pendingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('email', $request->email)
                    ->where('payment_status', 'pending')
                    ->first();
                if ($pendingRegistration) {
                    return redirect()->route('events.payment.show', $pendingRegistration)
                        ->with('info', 'You have a pending registration. Please complete payment.');
                }
            }
        }

        // ---------- Generate OTP and store only needed fields in session ----------
        $otp = rand(100000, 999999);

        // format mobile for SMS provider (strip non-digits, prefix country code if needed)
        $mobileDigits = preg_replace('/\D+/', '', $request->mobile);
        if (strlen($mobileDigits) === 10) {
            $smsMobile = '91' . $mobileDigits; // India default
        } else {
            $smsMobile = $mobileDigits; // fallback (assume caller provided country code)
        }

        $storedData = $request->only([
            'first_name',
            'middle_name',
            'last_name',
            'dob',
            'address',
            'email',
            'mobile',
            'school',
            'grade',
            'sport_event',
            'age_category'
        ]);

        session([
            'event_registration_data' => $storedData,
            'event_id'                => $event->id,
            'otp'                     => $otp,
            'otp_mobile'              => $smsMobile, // used for sending
            'otp_expires_at'          => now()->addMinutes(5),
        ]);

        // send OTP (throws on failure)
        try {
            $this->sendOtp($smsMobile, $otp);
            Log::info('OTP sent to mobile', ['mobile' => $smsMobile, 'event' => $event->id]);
        } catch (\Exception $e) {
            Log::error('OTP sending failed', ['error' => $e->getMessage()]);
            // clear session bits to avoid confusing state
            session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
            return redirect()->route('events.show', $event)
                ->with('error', 'Failed to send OTP. Please try again later.');
        }

        // Redirect to OTP verification page and STOP — do NOT continue with registration here
        return redirect()->route('events.register.verifyOtp', $event)
            ->with('info', 'OTP sent to your mobile. Please verify to continue.');
    }

    public function processBook_old(Request $request, Event $event)
    {
        $rules = [
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'dob'          => 'required|date|before:today',
            'address'      => 'required|string',
            'email'        => 'required|email|max:255',
            'mobile'       => 'required|string|max:20',
        ];

        if ($event->category === 'general') {
            $rules['terms_conditions'] = 'required|accepted';
        }

        $request->validate($rules);

        session(['new_registration' => 0]);

        // ---------- AUTH / AUTO REGISTER ----------
        if (!auth()->check()) {

            $user = User::where('email', $request->email)
                // ->orWhere('phone', $request->mobile)
                ->where('role', 'user')
                ->first();

            if (!$user) {
                // ✅ Generate plain password
                $plainPassword = $this->generateStrongPassword(10);

                $user = User::create([
                    'name'     => $request->first_name . ' ' . $request->last_name,
                    'email'    => $request->email,
                    'phone'    => $request->mobile,
                    'location' => $request->address,
                    'role'     => 'user',
                    'password' => Hash::make($plainPassword), // ONLY hash stored
                ]);

                // Set the session variable
                session(['new_registration' => 1]);
                session(['plainPassword' => $plainPassword]);

            }

            Auth::login($user);
        }

        Log::info('Processing Event Registration', [
            'event_id' => $event->id,
            'category'  => $event->category,
            'user_id'   => auth()->id(),
            'is_auth'   => auth()->check(),
            'request_data' => $request->except(['_token'])
        ]);

        // ---------- IMPORTANT: Run seat-limit + duplicate checks BEFORE sending OTP ----------
        // Seat limit
        if ($event->seat_limit && $event->registrations()->where('payment_status', 'completed')->count() >= $event->seat_limit) {
            return redirect()->route('events.show', $event)->with('error', 'Sorry, this event is now full.');
        }

        // Duplicate / pending checks (same logic as before)
        $paidStatuses = ['paid', 'completed'];

        if (auth()->check()) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->whereIn('payment_status', $paidStatuses)
                ->first();
            if ($existingRegistration) {
                return redirect()->route('events.show', $event)
                    ->with('info', 'You are already registered for this event.');
            }

            // for paid events, check for pending registration
            if ($event->registration_amount > 0) {
                $pendingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', auth()->id())
                    ->where('payment_status', 'pending')
                    ->first();
                if ($pendingRegistration) {
                    return redirect()->route('events.payment.show', $pendingRegistration)
                        ->with('info', 'You have a pending registration. Please complete payment.');
                }
            }
        } else {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('email', $request->email)
                ->whereIn('payment_status', $paidStatuses)
                ->first();
            if ($existingRegistration) {
                return redirect()->route('events.show', $event)
                    ->with('info', 'This email is already registered for this event.');
            }

            if ($event->registration_amount > 0) {
                $pendingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('email', $request->email)
                    ->where('payment_status', 'pending')
                    ->first();
                if ($pendingRegistration) {
                    return redirect()->route('events.payment.show', $pendingRegistration)
                        ->with('info', 'You have a pending registration. Please complete payment.');
                }
            }
        }

        // ---------- Generate OTP and store only needed fields in session ----------
        $otp = rand(100000, 999999);

        // format mobile for SMS provider (strip non-digits, prefix country code if needed)
        $mobileDigits = preg_replace('/\D+/', '', $request->mobile);
        if (strlen($mobileDigits) === 10) {
            $smsMobile = '91' . $mobileDigits; // India default
        } else {
            $smsMobile = $mobileDigits; // fallback (assume caller provided country code)
        }

        $storedData = $request->only([
            'first_name',
            'middle_name',
            'last_name',
            'dob',
            'address',
            'email',
            'mobile'
        ]);

        session([
            'event_registration_data' => $storedData,
            'event_id'                => $event->id,
            'otp'                     => $otp,
            'otp_mobile'              => $smsMobile, // used for sending
            'otp_expires_at'          => now()->addMinutes(5),
        ]);

        // send OTP (throws on failure)
        try {
            $this->sendOtp($smsMobile, $otp);
            Log::info('OTP sent to mobile', ['mobile' => $smsMobile, 'event' => $event->id]);
        } catch (\Exception $e) {
            Log::error('OTP sending failed', ['error' => $e->getMessage()]);
            // clear session bits to avoid confusing state
            session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
            return redirect()->route('events.show', $event)
                ->with('error', 'Failed to send OTP. Please try again later.');
        }

        // Redirect to OTP verification page and STOP — do NOT continue with registration here
        return redirect()->route('events.register.verifyOtp', $event)
            ->with('info', 'OTP sent to your mobile. Please verify to continue.');
    }

    public function processBook(Request $request, Event $event)
    {
        // Prevent registration for general events
        // if ($event->category === 'general') {
        //     return redirect()->route('events.show', $event)
        //         ->with('error', 'Registration is not available for general events.');
        // }

        Log::info('Processing Event Registration', [
            'event_id' => $event->id,
            'category'  => $event->category,
            'user_id'   => auth()->id(),
            'is_auth'   => auth()->check(),
            'request_data' => $request->except(['_token'])
        ]);

       $rules = [
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'dob'          => 'required|date|before:today',
            'address'      => 'required|string',
            'email'        => 'required|email|max:255',
            'mobile'       => 'required|string|max:20',
        ];

        if ($event->category === 'yc') {
            $rules = [
                'school'       => 'required|string|max:255',
                'grade'        => 'required|string|max:50',
                'sport_event'  => 'required|string|max:255',
                'age_category' => 'required|string|max:50',
            ];
        }

        $request->validate($rules);

        session(['new_registration' => 0]);

        // ---------- AUTH / AUTO REGISTER ----------
        if (!auth()->check()) {

            $user = User::where('email', $request->email)
            // ->orWhere('phone', $request->mobile)
            ->first();

            if ($user) {
            
                if ($user->role == 'vendor') {
            
                    return back()->withErrors([
                        'email' => 'This email address is already registered as a vendor account. Please register using another email address.'
                    ]);
            
                } elseif ($user->role == 'admin') {
            
                    return back()->withErrors([
                        'email' => 'This email address is already associated with an admin account. Please register using another email address.'
                    ]);
            
                } elseif ($user->role == 'user') {
            
                    // Existing normal user
                    Auth::login($user);
            
                }
            
            } else {
            
                // ✅ Generate plain password
                $plainPassword = $this->generateStrongPassword(10);

                $user = User::create([
                    'name'     => $request->first_name . ' ' . $request->last_name,
                    'email'    => $request->email,
                    'phone'    => $request->mobile,
                    'location' => $request->address,
                    'role'     => 'user',
                    'password' => Hash::make($plainPassword), // ONLY hash stored
                ]);

                // Set the session variable
                session(['new_registration' => 1]);
                session(['plainPassword' => $plainPassword]);
                
                Auth::login($user);
            }
        }

        // ---------- IMPORTANT: Run seat-limit + duplicate checks BEFORE sending OTP ----------
        // Seat limit
        if ($event->seat_limit && $event->registrations()->where('payment_status', 'completed')->count() >= $event->seat_limit) {
            return redirect()->route('events.show', $event)->with('error', 'Sorry, this event is now full.');
        }

        // Duplicate / pending checks (same logic as before)
        $paidStatuses = ['paid', 'completed'];
        if (auth()->check()) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->whereIn('payment_status', $paidStatuses)
                ->first();
            if ($existingRegistration) {
                return redirect()->route('events.show', $event)
                    ->with('info', 'You are already registered for this event.');
            }

            if ($event->category === 'yc') {
                $pendingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', auth()->id())
                    ->where('payment_status', 'pending')
                    ->first();
                if ($pendingRegistration) {
                    return redirect()->route('events.payment.show', $pendingRegistration)
                        ->with('info', 'You have a pending registration. Please complete payment.');
                }
            }

            // for paid events, check for pending registration
            if ($event->category === 'general') {
                if ($event->registration_amount > 0) {
                    $pendingRegistration = EventRegistration::where('event_id', $event->id)
                        ->where('user_id', auth()->id())
                        ->where('payment_status', 'pending')
                        ->first();
                    if ($pendingRegistration) {
                        return redirect()->route('events.payment.show', $pendingRegistration)
                            ->with('info', 'You have a pending registration. Please complete payment.');
                    }
                }
            }
        } else {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('email', $request->email)
                ->whereIn('payment_status', $paidStatuses)
                ->first();
            if ($existingRegistration) {
                return redirect()->route('events.show', $event)
                    ->with('info', 'This email is already registered for this event.');
            }

            if ($event->category === 'yc') {
                $pendingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('email', $request->email)
                    ->where('payment_status', 'pending')
                    ->first();
                if ($pendingRegistration) {
                    return redirect()->route('events.payment.show', $pendingRegistration)
                        ->with('info', 'You have a pending registration. Please complete payment.');
                }
            }

            if ($event->category === 'general') {
                if ($event->registration_amount > 0) {
                    $pendingRegistration = EventRegistration::where('event_id', $event->id)
                        ->where('email', $request->email)
                        ->where('payment_status', 'pending')
                        ->first();
                    if ($pendingRegistration) {
                        return redirect()->route('events.payment.show', $pendingRegistration)
                            ->with('info', 'You have a pending registration. Please complete payment.');
                    }
                }
            }
        }

        // ---------- Generate OTP and store only needed fields in session ----------
        $otp = rand(100000, 999999);

        // format mobile for SMS provider (strip non-digits, prefix country code if needed)
        $mobileDigits = preg_replace('/\D+/', '', $request->mobile);
        if (strlen($mobileDigits) === 10) {
            $smsMobile = '91' . $mobileDigits; // India default
        } else {
            $smsMobile = $mobileDigits; // fallback (assume caller provided country code)
        }

        $fields = [
            'first_name',
            'middle_name',
            'last_name',
            'dob',
            'address',
            'email',
            'mobile'
        ];

        if ($event->category === 'yc') {
            $fields = array_merge($fields, [
                'school',
                'grade',
                'sport_event',
                'age_category'
            ]);
        }

        $storedData = $request->only($fields);

        session([
            'event_registration_data' => $storedData,
            'event_id'                => $event->id,
            'otp'                     => $otp,
            'otp_mobile'              => $smsMobile, // used for sending
            'otp_expires_at'          => now()->addMinutes(5),
        ]);

        // send OTP (throws on failure)
        try {
            $this->sendOtp($smsMobile, $otp);
            Log::info('OTP sent to mobile', ['mobile' => $smsMobile, 'event' => $event->id]);
        } catch (\Exception $e) {
            Log::error('OTP sending failed', ['error' => $e->getMessage()]);
            // clear session bits to avoid confusing state
            session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
            return redirect()->route('events.show', $event)
                ->with('error', 'Failed to send OTP. Please try again later.');
        }

        // Redirect to OTP verification page and STOP — do NOT continue with registration here
        return redirect()->route('events.register.verifyOtp', $event)
            ->with('info', 'OTP sent to your mobile. Please verify to continue.');
    }

    private function generateStrongPassword(int $length = 10): string
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{' . $length . ',}$/';

        do {
            $password = Str::random($length);

            // Inject required characters to guarantee regex match
            $password = substr_replace($password, chr(rand(65, 90)), 0, 1); // Uppercase
            $password = substr_replace($password, chr(rand(97, 122)), 1, 1); // Lowercase
            $password = substr_replace($password, rand(0, 9), 2, 1); // Number
            $password = substr_replace($password, '@', 3, 1); // Special char

            $password = str_shuffle($password);

        } while (!preg_match($regex, $password));

        return $password;
    }

    /**
     * Send OTP using MsgClub (use query params to avoid manual URL concat)
     */
    private function sendOtp(string $mobileWithCountryCode, string|int $otp)
    {
        $apiKey   = config('services.msgclub.auth_key');  // .env
        $senderId = config('services.msgclub.sender_id'); // .env

        $message = "Dear user, Welcome to YOUTHCENTUARY! To complete your registration for YC SPARK 2025, please enter OTP: $otp. Do not share this OTP to anyone for security reasons.";

        $client = new \GuzzleHttp\Client(['timeout' => 15]);

        $url = 'https://msg.msgclub.net/rest/services/sendSMS/sendGroupSms';

        try {
            Log::debug('Sending OTP request', [
                'url'    => $url,
                'params' => [
                    'AUTH_KEY'       => $apiKey,
                    'message'        => $message,
                    'senderId'       => $senderId,
                    'routeId'        => '8',
                    'mobileNos'      => $mobileWithCountryCode,
                    'smsContentType' => 'english',
                ]
            ]);

            $response = $client->request('GET', $url, [
                'query' => [
                    'AUTH_KEY'       => $apiKey,
                    'message'        => $message,
                    'senderId'       => $senderId,
                    'routeId'        => '8',
                    'mobileNos'      => $mobileWithCountryCode,
                    'smsContentType' => 'english',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body       = (string) $response->getBody();

            Log::debug('MsgClub response', [
                'status_code' => $statusCode,
                'body'        => $body,
            ]);

            if ($statusCode !== 200) {
                throw new \Exception("HTTP $statusCode - $body");
            }

            // Optional: parse JSON to validate response code
            $json = json_decode($body, true);
            if (!$json || ($json['responseCode'] ?? '') !== '3001') {
                throw new \Exception('MsgClub returned unsuccessful response: ' . $body);
            }
        } catch (\Exception $e) {
            Log::error('OTP sending failed', [
                'mobile'  => $mobileWithCountryCode,
                'otp'     => $otp,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);

            throw $e; // propagate to caller
        }
    }

    /**
     * Show OTP form
     */
    public function showOtpForm(Event $event)
    {
        return view('public.events.verify-otp', compact('event'));
    }

    /**
     * Verify OTP and finalize registration (calls finalizeRegistration)
     */
    public function verifyOtp(Request $request, Event $event)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $storedOtp = session('otp');
        $expiresAt = session('otp_expires_at');

        if (!$storedOtp || !$expiresAt) {
            return redirect()->route('events.show', $event)
                ->with('error', 'No OTP request in progress. Please re-submit registration.');
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
            return back()->withErrors(['otp' => 'OTP expired. Please re-submit the registration to get a new OTP.']);
        }

        if ((string) $request->otp !== (string) $storedOtp) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // OTP OK → finalize using saved data
        $data = session('event_registration_data', []);
        // call finalize which contains your DB logic (refactored)
        return $this->finalizeRegistration($event, $data);
    }

    /**
     * Finalize registration (moved your DB logic here).
     * $data is array from session (not a Request)
     */
    protected function finalizeRegistration(Event $event, array $data)
    {
        // Extra safety: re-check seat-limit (race condition)
        if ($event->seat_limit && $event->registrations()->where('payment_status', 'completed')->count() >= $event->seat_limit) {
            session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
            return redirect()->route('events.show', $event)->with('error', 'Sorry, this event is now full.');
        }

        DB::beginTransaction();
        try {
            if(isset($data['age_category']) && !empty($data['age_category'])){
                $age_category = $data['age_category'];
            } else{
                $age_category = null;
            }

            if(isset($data['grade']) && !empty($data['grade'])){
                $grade = $data['grade'];
            } else{
                $grade = null;
            }

            if(isset($data['school']) && !empty($data['school'])){
                $school = $data['school'];
            } else{
                $school = null;
            }

            if(isset($data['sport_event']) && !empty($data['sport_event'])){
                $sport_event = $data['sport_event'];
            } else{
                $sport_event = null;
            }

            $registrationData = [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'dob' => $data['dob'],
                'age_category' => $age_category,
                'address' => $data['address'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'school' => $school,
                'grade' => $grade,
                'sport_event' => $sport_event,
                'razorpay_order_id' => null,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'payment_method' => null,
            ];

            // Use registration_amount to decide paid vs free
            if ($event->registration_amount > 0) {

                if(isset($data['age_category']) && !empty($data['age_category'])){
                    // --- Paid Event Logic ---
                    $selectedSchoolType = DB::table('school_types')->where('id', $data['age_category'])->first();
                    if (!$selectedSchoolType) {
                        DB::rollBack();
                        session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
                        return redirect()->route('events.show', $event)
                            ->with('error', 'Invalid school type selected.');
                    }

                    $registrationData['amount'] = $selectedSchoolType->price;
                    $registrationData['payment_status'] = 'pending';
                    $registrationData['school_type_id'] = $selectedSchoolType->id;
                } else {
                    $registrationData['amount'] = $event->registration_amount;
                    $registrationData['payment_status'] = 'pending';
                    $registrationData['school_type_id'] = null;
                }

                $registration = EventRegistration::create($registrationData);

                $receiptId = 'reg_yc_' . $registration->id;
                $notes = [
                    'event_id' => $event->id,
                    'registration_id' => $registration->id,
                    'email' => $data['email'],
                    'name' => $data['first_name'] . ' ' . ($data['last_name'] ?? ''),
                    'event_name' => $event->title
                ];

                // ---------- SEND EMAILS ----------
                if(session('new_registration') == 1){
                    $user = auth()->user();
                    $plainPassword = session('plainPassword');
                    try {
                        // Send welcome email with password
                        Mail::to($user->email)->send(
                            new UserRegistered($user, $plainPassword)
                        );

                        // Admin notification
                        $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                        if (!empty($adminEmails)) {
                            Mail::to($adminEmails)->send(new AdminNotification(
                                'New User Registration',
                                [
                                    'type' => 'user_registration',
                                    'message' => "A new user has registered: {$user->name}",
                                    'details' => [
                                        'name' => $user->name,
                                        'email' => $user->email,
                                        'phone' => $user->phone,
                                        'location' => $user->location,
                                        'registered_at' => $user->created_at->format('F j, Y g:i A'),
                                    ],
                                    'action_url' => route('admin.users.index'),
                                    'action_text' => 'View Users'
                                ]
                            ));
                        }

                        Log::info('User registration emails sent', ['user_id' => $user->id]);

                    } catch (\Throwable $mailError) {
                        Log::error('Failed to send user registration emails', [
                            'error' => $mailError->getMessage(),
                            'user_id' => $user->id
                        ]);
                    }

                    session()->forget(['new_registration', 'plainPassword']);
                }

                try {
                    $orderData = $this->razorpayService->createOrder($registrationData['amount'], $receiptId, $notes);
                    $registration->update(['razorpay_order_id' => $orderData['id']]);

                    DB::commit();
                    session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
                    Log::info('Paid registration created, redirecting to payment', [
                        'registration_id' => $registration->id,
                        'order_id' => $orderData['id']
                    ]);
                    return redirect()->route('events.payment.show', $registration->id)
                        ->with('success', 'Registration information saved. Please complete payment.');
                } catch (\Exception $razorpayError) {
                    DB::rollBack();
                    Log::error('Razorpay Order Creation Error', [
                        'message' => $razorpayError->getMessage(),
                        'trace' => $razorpayError->getTraceAsString(),
                        'registration_id' => $registration->id ?? null
                    ]);
                    session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
                    return redirect()->route('events.show', $event)
                        ->with('error', 'There was an error processing your payment. Please try again later.');
                }
            } else {
                // --- Free Event Logic (registration_amount empty or 0) ---
                $registrationData['amount'] = 0;
                $registrationData['payment_status'] = 'completed';
                $registration = EventRegistration::create($registrationData);

                // ---------- SEND EMAILS ----------
                if(session('new_registration') == 1){
                    $user = auth()->user();
                    $plainPassword = session('plainPassword');
                    try {
                        // Send welcome email with password
                        Mail::to($user->email)->send(
                            new UserRegistered($user, $plainPassword)
                        );

                        // Admin notification
                        $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                        if (!empty($adminEmails)) {
                            Mail::to($adminEmails)->send(new AdminNotification(
                                'New User Registration',
                                [
                                    'type' => 'user_registration',
                                    'message' => "A new user has registered: {$user->name}",
                                    'details' => [
                                        'name' => $user->name,
                                        'email' => $user->email,
                                        'phone' => $user->phone,
                                        'location' => $user->location,
                                        'registered_at' => $user->created_at->format('F j, Y g:i A'),
                                    ],
                                    'action_url' => route('admin.users.index'),
                                    'action_text' => 'View Users'
                                ]
                            ));
                        }

                        Log::info('User registration emails sent', ['user_id' => $user->id]);

                    } catch (\Throwable $mailError) {
                        Log::error('Failed to send user registration emails', [
                            'error' => $mailError->getMessage(),
                            'user_id' => $user->id
                        ]);
                    }

                    session()->forget(['new_registration', 'plainPassword']);
                }

                DB::commit();
                session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
                Log::info('Free registration created successfully', [
                    'registration_id' => $registration->id
                ]);

                // Small delay before final email
                sleep(1);

                // Send confirmation email to registrant
                Mail::to($registration->email)->send(new EventRegistrationSuccess($registration));

                // Send notification to admin
                // $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                // if (!empty($adminEmails)) {
                    
                //     if($registration->amount && $registration->amount > 0){
                //         $amt = '₹' . number_format($registration->amount, 2);
                //     } else {
                //         $amt = 'Free Event';
                //     }

                //     Mail::to($adminEmails)->send(new AdminNotification(
                //         'New Event Registration',
                //         [
                //             'type' => 'event_registration',
                //             'message' => "New registration for event: {$registration->event->title}",
                //             'details' => [
                //                 'event_title' => $registration->event->title,
                //                 'participant_name' => $registration->first_name . ' ' . $registration->last_name,
                //                 'participant_email' => $registration->email,
                //                 'participant_phone' => $registration->mobile,
                //                 'amount_paid' => $amt,
                //                 'payment_id' => $registration->razorpay_payment_id,
                //                 'registration_id' => $registration->id,
                //                 'event_date' => $registration->event->start_date->format('F j, Y g:i A'),
                //             ],
                //             'action_url' => route('admin.events.participants', $registration->event),
                //             'action_text' => 'View Participants'
                //         ]
                //     ));
                // }
                
                // // Send notification to host email
                // $hostEmail = config('mail.from.address');
                // if ($hostEmail && $hostEmail !== 'hello@example.com') {

                //     if($registration->amount && $registration->amount > 0){
                //         $amt = '₹' . number_format($registration->amount, 2);
                //     } else {
                //         $amt = 'Free Event';
                //     }

                //     Mail::to($hostEmail)->send(new AdminNotification(
                //         'Event Registration Notification',
                //         [
                //             'type' => 'event_registration',
                //             'message' => "New registration: {$registration->first_name} {$registration->last_name}",
                //             'details' => [
                //                 'event' => $registration->event->title,
                //                 'participant' => "{$registration->first_name} {$registration->last_name} ({$registration->email})",
                //                 'amount' => $amt,
                //             ]
                //         ]
                //     ));
                // }

                return redirect()->route('events.show', $event)
                    ->with('success', 'You have successfully registered for this event! (RSVP received)');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Event Registration Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'event_id' => $event->id,
                'user_data' => $data
            ]);
            session()->forget(['event_registration_data', 'event_id', 'otp', 'otp_mobile', 'otp_expires_at']);
            return redirect()->route('events.show', $event)
                ->with('error', 'An error occurred during registration. Please try again later.');
        }
    }

    /**
     * Display the payment page for a registration
     *
     * @param  \App\Models\EventRegistration|int  $registration
     * @return \Illuminate\Http\Response
     */
    public function showPaymentPage($registration)
    {
        // Add comprehensive debugging
        \Log::debug('ShowPaymentPage called with:', [
            'registration_param' => $registration,
            'is_numeric' => is_numeric($registration),
            'request_path' => request()->path(),
            'request_url' => request()->url(),
            'referer' => request()->header('referer'),
            'user_id' => auth()->id()
        ]);
        
        // Check if $registration is an Event ID or Registration ID
        if (is_numeric($registration) && !$registration instanceof \App\Models\EventRegistration) {
            $registrationRecord = \App\Models\EventRegistration::find($registration);
            
            // If we found a registration with this ID, use it
            if ($registrationRecord) {
                $registration = $registrationRecord;
                Log::info('Found registration record by ID', ['registration_id' => $registration->id]);
            } else {
                // Otherwise, treat it as an event ID and try to find the user's pending registration
                if (auth()->check()) {
                    $userRegistration = \App\Models\EventRegistration::where('event_id', $registration)
                        ->where('user_id', auth()->id())
                        ->where('payment_status', 'pending')
                        ->latest()
                        ->first();
                    
                    if ($userRegistration) {
                        $registration = $userRegistration;
                        Log::info('Found user registration for event', [
                            'event_id' => $registration, 
                            'registration_id' => $userRegistration->id
                        ]);
                    } else {
                        // No existing registration, redirect to event page
                        Log::info('No pending registration found for event', ['event_id' => $registration]);
                        return redirect()->route('events.show', Event::findOrFail($registration))
                            ->with('error', 'Please complete the registration form first.');
                    }
                } else {
                    // If guest, redirect to event registration
                    return redirect()->route('events.show', Event::findOrFail($registration))
                        ->with('error', 'Please complete the registration form first.');
                }
            }
        }
        
        // Now proceed with a valid registration object
        if (!($registration instanceof \App\Models\EventRegistration)) {
            $registration = \App\Models\EventRegistration::findOrFail($registration);
        }
        
        // Check if this registration belongs to the logged in user or is a guest registration
        if (auth()->check() && $registration->user_id && $registration->user_id != auth()->id()) {
            Log::info('Registration belongs to different user', [
                'registration_user_id' => $registration->user_id,
                'current_user_id' => auth()->id()
            ]);
            return redirect()->route('events.show', $registration->event)
                ->with('error', 'This registration belongs to a different user.');
        }
        
        if ($registration->payment_status === 'paid') {
            return redirect()->route('user.events.success', $registration->id)
                ->with('info', 'This registration has already been completed.');
        }
        
        try {
            $event = $registration->event;
            
            // Fetch order details from Razorpay
            $order = $this->razorpayService->fetchOrder($registration->razorpay_order_id);
            
            // return view with the registration and order
            return view('public.events.payment', compact('registration', 'event', 'order'));
        } catch (\Exception $e) {
            Log::error('Payment Page Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'registration_id' => $registration->id
            ]);
            
            return redirect()->route('events.show', $registration->event)
                ->with('error', 'There was an error preparing your payment. Please try again later.');
        }
    }

    public function handlePayment(Request $request)
    {
        try {
            // Add debug logging
            Log::info('Payment handling initiated', [
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'has_signature' => !empty($request->razorpay_signature),
                'user_id' => auth()->id()
            ]);
            
            $registration = EventRegistration::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();
            
            // Check if this is a mock order (for testing environments)
            $isMockOrder = strpos($request->razorpay_order_id ?? '', 'order_mock_') === 0;
            $isMockPayment = strpos($request->razorpay_payment_id ?? '', 'pay_mock_') === 0;
            
            if ($isMockOrder && $isMockPayment) {
                // For mock orders, skip verification and mark as paid
                Log::info('Processing mock payment for testing', [
                    'registration_id' => $registration->id,
                    'mock_payment_id' => $request->razorpay_payment_id
                ]);
                
                // Update registration as paid
                $registration->update([
                    'payment_status' => 'paid',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                ]);
                
                // Send confirmation email and notifications
                try {
                    // Send confirmation email to registrant
                    Mail::to($registration->email)->send(new EventRegistrationSuccess($registration));
                    
                    // Send notification to admin
                    $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                    if (!empty($adminEmails)) {
                        Mail::to($adminEmails)->send(new AdminNotification(
                            'New Event Registration',
                            [
                                'type' => 'event_registration',
                                'message' => "New registration for event: {$registration->event->title}",
                                'details' => [
                                    'event_title' => $registration->event->title,
                                    'participant_name' => $registration->first_name . ' ' . $registration->last_name,
                                    'participant_email' => $registration->email,
                                    'participant_phone' => $registration->mobile,
                                    'amount_paid' => '₹' . number_format($registration->amount, 2),
                                    'payment_id' => $registration->razorpay_payment_id,
                                    'registration_id' => $registration->id,
                                    'event_date' => $registration->event->start_date->format('F j, Y g:i A'),
                                ],
                                'action_url' => route('admin.events.participants', $registration->event),
                                'action_text' => 'View Participants'
                            ]
                        ));
                    }
                    
                    // Send notification to host email
                    $hostEmail = config('mail.from.address');
                    if ($hostEmail && $hostEmail !== 'hello@example.com') {
                        Mail::to($hostEmail)->send(new AdminNotification(
                            'Event Registration Notification',
                            [
                                'type' => 'event_registration',
                                'message' => "New registration: {$registration->first_name} {$registration->last_name}",
                                'details' => [
                                    'event' => $registration->event->title,
                                    'participant' => "{$registration->first_name} {$registration->last_name} ({$registration->email})",
                                    'amount' => '₹' . number_format($registration->amount, 2),
                                ]
                            ]
                        ));
                    }
                    
                } catch (\Exception $mailError) {
                    Log::error('Failed to send event registration emails', [
                        'error' => $mailError->getMessage(),
                        'registration_id' => $registration->id
                    ]);
                }
                
                return redirect()->route('user.events.success', $registration->id)
                    ->with('success', 'Test payment completed successfully!');
            }
            
            if ($request->razorpay_payment_id && $request->razorpay_signature) {
                // Verify the payment signature
                $verified = $this->razorpayService->verifySignature(
                    $request->razorpay_payment_id,
                    $request->razorpay_order_id,
                    $request->razorpay_signature
                );
                
                if ($verified) {
                    // Update registration as paid
                    $registration->update([
                        'payment_status' => 'paid',
                        'razorpay_payment_id' => $request->razorpay_payment_id,
                    ]);
                    
                    // Send confirmation email and notifications
                    try {
                        // Send confirmation email to registrant
                        Mail::to($registration->email)->send(new EventRegistrationSuccess($registration));
                        
                        // Send notification to admin
                        $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                        if (!empty($adminEmails)) {
                            Mail::to($adminEmails)->send(new AdminNotification(
                                'New Event Registration',
                                [
                                    'type' => 'event_registration',
                                    'message' => "New registration for event: {$registration->event->title}",
                                    'details' => [
                                        'event_title' => $registration->event->title,
                                        'participant_name' => $registration->first_name . ' ' . $registration->last_name,
                                        'participant_email' => $registration->email,
                                        'participant_phone' => $registration->mobile,
                                        'amount_paid' => '₹' . number_format($registration->amount, 2),
                                        'payment_id' => $registration->razorpay_payment_id,
                                        'registration_id' => $registration->id,
                                        'event_date' => $registration->event->start_date->format('F j, Y g:i A'),
                                    ],
                                    'action_url' => route('admin.events.participants', $registration->event),
                                    'action_text' => 'View Participants'
                                ]
                            ));
                        }
                        
                        // Send notification to host email
                        $hostEmail = config('mail.from.address');
                        if ($hostEmail && $hostEmail !== 'hello@example.com') {
                            Mail::to($hostEmail)->send(new AdminNotification(
                                'Event Registration Notification',
                                [
                                    'type' => 'event_registration',
                                    'message' => "New registration: {$registration->first_name} {$registration->last_name}",
                                    'details' => [
                                        'event' => $registration->event->title,
                                        'participant' => "{$registration->first_name} {$registration->last_name} ({$registration->email})",
                                        'amount' => '₹' . number_format($registration->amount, 2),
                                    ]
                                ]
                            ));
                        }
                        
                    } catch (\Exception $mailError) {
                        Log::error('Failed to send event registration emails', [
                            'error' => $mailError->getMessage(),
                            'registration_id' => $registration->id
                        ]);
                    }
                    
                    Log::info('Payment successful', [
                        'registration_id' => $registration->id,
                        'payment_id' => $request->razorpay_payment_id
                    ]);
                    
                    return redirect()->route('user.events.success', $registration->id)
                        ->with('success', 'Registration completed successfully!');
                } else {
                    // Signature verification failed
                    $registration->update(['payment_status' => 'failed']);
                    
                    Log::error('Razorpay Signature Verification Failed', [
                        'registration_id' => $registration->id,
                        'payment_id' => $request->razorpay_payment_id
                    ]);
                    
                    return redirect()->route('events.show', $registration->event)
                        ->with('error', 'Payment verification failed. Please contact support.');
                }
            }
            
            // Payment was unsuccessful
            $registration->update(['payment_status' => 'failed']);
            
            Log::warning('Payment failed - no payment ID or signature', [
                'registration_id' => $registration->id,
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('events.show', $registration->event)
                ->with('error', 'Payment failed. Please try again.');
        } catch (\Exception $e) {
            Log::error('Payment Handling Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payment_data' => $request->all()
            ]);
            
            return redirect()->route('events.index')
                ->with('error', 'An error occurred while processing your payment. Please contact support.');
        }
    }
    
    public function success($id)
    {
        $registration = EventRegistration::findOrFail($id);
        
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('events.show', $registration->event)
                ->with('error', 'This registration has not been completed.');
        }
        
        // Fetch data needed for the public layout navbar
        $categories = \App\Models\Category::with('subcategories')->get();
        $popularCities = \App\Models\City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();
        
        return view('public.events.success', compact('registration', 'categories', 'popularCities'));
    }
    
    public function failure($id)
    {
        $registration = EventRegistration::findOrFail($id);
        
        // Update registration status to failed if not already
        if ($registration->payment_status === 'pending') {
            $registration->update(['payment_status' => 'failed']);
        }
        
        // Fetch data needed for the public layout navbar
        $categories = \App\Models\Category::with('subcategories')->get();
        $popularCities = \App\Models\City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();
            
        return view('public.events.failure', compact('registration', 'categories', 'popularCities'));
    }
    
    public function userRegistrations()
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Fetch categories needed for the navbar
        $categories = \App\Models\Category::with('subcategories')->get();
            
        return view('user.events.my-registrations', compact('registrations', 'categories'));
    }
    
    /**
     * Download a PDF receipt for event registration
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt($id)
    {
        $registration = EventRegistration::with('event')->findOrFail($id);
        
        // Check if this registration belongs to the logged in user (if authenticated)
        if (auth()->check() && $registration->user_id && $registration->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to download this receipt.');
        }
        
        // Ensure the registration is paid
        if ($registration->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Receipt is only available for completed payments.');
        }

        if (config('app.currency_symbol')) {
            $currency_symbol = config('app.currency_symbol');
        } else {
            $currency_symbol = 'Rs';
        }
        
        $data = [
            'registration' => $registration,
            'event' => $registration->event,
            'currency_symbol' => $currency_symbol,
            'issued_date' => now()->format('M d, Y'),
            'company_name' => config('app.name'),
            'company_address' => "A Youthcentuary Academy Pvt. Ltd. Company" . "\n" . 
                         "Navi Mumbai, Maharashtra",
            'company_email' => 'info@youthcentral.co',
            'receipt_id' => 'RCPT-' . $registration->id . '-' . substr($registration->razorpay_payment_id, -6)
        ];
        
        $pdf = PDF::loadView('pdfs.event-receipt', $data);
        
        return $pdf->download('event-receipt-' . $registration->id . '.pdf');
    }

    /**
     * Debug method to check registration details (remove in production)
     */
    public function debugRegistration($id)
    {
        // Only available in non-production environments
        if (app()->environment('production')) {
            abort(404);
        }
        
        $registration = EventRegistration::with('event')->findOrFail($id);
        
        // Return registration details as JSON
        return response()->json([
            'registration' => $registration,
            'payment_url' => route('events.payment.show', $registration->id),
            'debug_info' => [
                'has_razorpay_order_id' => !empty($registration->razorpay_order_id),
                'razorpay_order_id' => $registration->razorpay_order_id,
                'status' => $registration->payment_status,
                'is_mock_order' => strpos($registration->razorpay_order_id ?? '', 'order_mock_') === 0,
                'razorpay_key_configured' => !empty(config('services.razorpay.key')),
                'app_environment' => app()->environment(),
            ]
        ]);
    }
}
