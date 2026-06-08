<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserRegistered;
use App\Mail\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the user registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    /**
     * Handle user registration request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register_old(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Send email notifications
        try {
            // Send welcome email to user
            Mail::to($user->email)->send(new UserRegistered($user));
            
            // Send notification to admin
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
            
            // Send notification to host email
            $hostEmail = config('mail.from.address');
            if ($hostEmail && $hostEmail !== 'hello@example.com') {
                Mail::to($hostEmail)->send(new AdminNotification(
                    'New User Registration Notification',
                    [
                        'type' => 'user_registration',
                        'message' => "New user registration: {$user->name}",
                        'details' => [
                            'user' => "{$user->name} ({$user->email})",
                            'phone' => $user->phone,
                            'location' => $user->location,
                        ]
                    ]
                ));
            }
            
            Log::info('User registration emails sent successfully', ['user_id' => $user->id]);
            
        } catch (\Exception $mailError) {
            Log::error('Failed to send user registration emails', [
                'error' => $mailError->getMessage(),
                'user_id' => $user->id
            ]);
        }

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    /**
     * Handle user registration request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            // new rules for coordinates
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Determine coordinates:
        // - Prefer values sent from the blade (hidden inputs).
        // - If missing, attempt server-side geocoding based on the provided 'location' text.
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Only attempt geocoding if one or both coordinates are missing and we have a location string
        if ((empty($latitude) || empty($longitude)) && !empty($request->input('location'))) {
            try {
                // Use OpenStreetMap Nominatim search endpoint to geocode the provided location string.
                // NOTE: Nominatim requires a valid User-Agent. Replace the string below with your app/domain.
                $response = Http::withHeaders([
                    'User-Agent' => 'YouthCentral/1.0 (your-domain.example)'
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $request->input('location'),
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1,
                ]);

                if ($response->successful()) {
                    $results = $response->json();
                    if (is_array($results) && count($results) > 0) {
                        $first = $results[0];
                        // Nominatim returns lat/lon as strings
                        if (!empty($first['lat']) && !empty($first['lon'])) {
                            $latitude = (float) $first['lat'];
                            $longitude = (float) $first['lon'];
                        }
                    }
                } else {
                    // Optionally log non-200 responses for debugging
                    Log::warning('Geocoding failed (non-200) for registration', [
                        'location' => $request->input('location'),
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                // Log and silently continue — we don't want geocoding failure to stop registration
                Log::warning('Geocoding exception during registration', [
                    'location' => $request->input('location'),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // ✅ Generate plain password
        $plainPassword = $request->password;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            // store coordinates (nullable). cast to float when present.
            // 'latitude' => $latitude !== null && $latitude !== '' ? (float) $latitude : null,
            // 'longitude' => $longitude !== null && $longitude !== '' ? (float) $longitude : null,
            'password' => Hash::make($plainPassword),
            'role' => 'user',
        ]);

        // Send email notifications
        try {
            // Send welcome email to user
            Mail::to($user->email)->send(new UserRegistered($user, $plainPassword));
            
            // Send notification to admin
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
            
            // Send notification to host email
            $hostEmail = config('mail.from.address');
            if ($hostEmail && $hostEmail !== 'hello@example.com') {
                Mail::to($hostEmail)->send(new AdminNotification(
                    'New User Registration Notification',
                    [
                        'type' => 'user_registration',
                        'message' => "New user registration: {$user->name}",
                        'details' => [
                            'user' => "{$user->name} ({$user->email})",
                            'phone' => $user->phone,
                            'location' => $user->location,
                        ]
                    ]
                ));
            }
            
            Log::info('User registration emails sent successfully', ['user_id' => $user->id]);
            
        } catch (\Exception $mailError) {
            Log::error('Failed to send user registration emails', [
                'error' => $mailError->getMessage(),
                'user_id' => $user->id
            ]);
        }

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    /**
     * Show the user login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * Handle user login request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role !== 'user') {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'You do not have user access.',
                ]);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
} 