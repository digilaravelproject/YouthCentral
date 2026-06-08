<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VendorRegistered;
use App\Mail\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
     * Show the vendor registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('vendor.auth.register');
    }

    /**
     * Handle vendor registration request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => 'pending',
            'business_name' => $request->business_name,
            'business_address' => $request->business_address, 
            'gst_number' => $request->gst_number,
        ]);

        // Send email notifications
        try {
            // Send welcome email to vendor
            Mail::to($user->email)->send(new VendorRegistered($user));
            
            // Send notification to admin
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminNotification(
                    'New Vendor Registration',
                    [
                        'type' => 'vendor_registration',
                        'message' => "A new vendor has registered: {$user->business_name}",
                        'details' => [
                            'vendor_name' => $user->name,
                            'vendor_email' => $user->email,
                            'business_name' => $user->business_name,
                            'business_address' => $user->business_address,
                            'phone' => $user->phone,
                            'gst_number' => $user->gst_number,
                            'status' => $user->status,
                            'registered_at' => $user->created_at->format('F j, Y g:i A'),
                        ],
                        'action_url' => route('admin.vendors.pending'),
                        'action_text' => 'Review Vendor'
                    ]
                ));
            }
            
            // Send notification to host email
            $hostEmail = config('mail.from.address');
            if ($hostEmail && $hostEmail !== 'hello@example.com') {
                Mail::to($hostEmail)->send(new AdminNotification(
                    'New Vendor Registration Notification',
                    [
                        'type' => 'vendor_registration',
                        'message' => "New vendor registration: {$user->business_name}",
                        'details' => [
                            'vendor' => "{$user->name} ({$user->email})",
                            'business' => $user->business_name,
                            'phone' => $user->phone,
                            'status' => 'Pending Approval',
                        ]
                    ]
                ));
            }
            
            Log::info('Vendor registration emails sent successfully', ['user_id' => $user->id]);
            
        } catch (\Exception $mailError) {
            Log::error('Failed to send vendor registration emails', [
                'error' => $mailError->getMessage(),
                'user_id' => $user->id
            ]);
        }

        Auth::login($user);

        return redirect()->route('vendor.dashboard')->with('message', 'Your account has been created and is pending approval by the administrator.');
    }

    /**
     * Show the vendor login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('vendor.auth.login');
    }

    /**
     * Handle vendor login request.
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
            
            if ($user->role !== 'vendor') {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'You do not have vendor access.',
                ]);
            }
            
            // Check if vendor is approved
            if ($user->status !== 'approved') {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'Your vendor account is pending approval.',
                ]);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('vendor.dashboard'));
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