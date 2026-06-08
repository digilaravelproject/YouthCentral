<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VendorRegistered;
use App\Mail\AdminNotification;
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
        $this->middleware('guest')->except(['logout', 'showCreateUserForm', 'createUser', 'showCreateVendorForm', 
                                           'createVendor', 'pendingVendors', 'approveVendor', 'rejectVendor']);
        $this->middleware('auth')->except(['showLoginForm', 'login']);
        $this->middleware('role:admin')->except(['showLoginForm', 'login', 'logout']);
    }

    /**
     * Show the admin registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    /**
     * Handle admin registration request.
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
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request.
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
            
            if ($user->role !== 'admin') {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'You do not have admin access.',
                ]);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
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

        return redirect()->route('admin.login');
    }
    
    /**
     * Show form to create a new user
     * 
     * @return \Illuminate\View\View
     */
    public function showCreateUserForm()
    {
        return view('admin.users.create');
    }
    
    /**
     * Create a new user from admin dashboard
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully');
    }
    
    /**
     * Show form to create a new vendor
     * 
     * @return \Illuminate\View\View
     */
    public function showCreateVendorForm()
    {
        return view('admin.vendors.create');
    }
    
    /**
     * Create a new vendor from admin dashboard
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createVendor(Request $request)
    {
        // Prepare the auto_approve input for boolean validation
        $request->merge([
            'auto_approve' => $request->filled('auto_approve') && $request->input('auto_approve') === 'on',
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|numeric',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:1000',
            'gst_number' => 'nullable|string|max:255',
            'auto_approve' => 'nullable|boolean', // Now expects true/false
        ]);

        if ($validator->fails()) {
            // dd($validator->errors()->all()); // Reverted
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Status logic can now directly use the boolean auto_approve
        $status = $request->auto_approve ? 'approved' : 'pending';
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => $status,
            'business_name' => $request->business_name,
            'business_address' => $request->business_address,
            'gst_number' => $request->gst_number,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Vendor created successfully');
    }
    
    /**
     * Display list of pending vendors
     * 
     * @return \Illuminate\View\View
     */
    public function pendingVendors()
    {
        $pendingVendors = User::where('role', 'vendor')
                              ->where('status', 'pending')
                              ->get();
                              
        return view('admin.vendors.pending', compact('pendingVendors'));
    }
    
    /**
     * Approve a vendor
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveVendor($id)
    {
        $vendor = User::findOrFail($id);
        
        if ($vendor->role !== 'vendor') {
            return redirect()->back()->with('error', 'User is not a vendor');
        }
        
        $vendor->status = 'approved';
        $vendor->save();

        // Send email notifications
        try {
            // Send welcome email to vendor
            Mail::to($vendor->email)->send(new VendorRegistered($vendor));
            
            // Send notification to admin
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminNotification(
                    'New Vendor Approved',
                    [
                        'type' => 'vendor_registration',
                        'message' => "A new vendor has approved: {$vendor->business_name}",
                        'details' => [
                            'vendor_name' => $vendor->name,
                            'vendor_email' => $vendor->email,
                            'business_name' => $vendor->business_name,
                            'business_address' => $vendor->business_address,
                            'phone' => $vendor->phone,
                            'gst_number' => $vendor->gst_number,
                            'status' => $vendor->status,
                            'registered_at' => $vendor->created_at->format('F j, Y g:i A'),
                        ],
                        'action_url' => route('admin.vendors.approve'),
                        'action_text' => 'Approve Vendor'
                    ]
                ));
            }
            
            // Send notification to host email
            $hostEmail = config('mail.from.address');
            if ($hostEmail && $hostEmail !== 'hello@example.com') {
                Mail::to($hostEmail)->send(new AdminNotification(
                    'New Vendor Approve Notification',
                    [
                        'type' => 'vendor_registration',
                        'message' => "New vendor Approved: {$vendor->business_name}",
                        'details' => [
                            'vendor' => "{$vendor->name} ({$vendor->email})",
                            'business' => $vendor->business_name,
                            'phone' => $vendor->phone,
                            'status' => 'Approval Done',
                        ]
                    ]
                ));
            }
            
            Log::info('Vendor approval emails sent successfully', ['user_id' => $vendor->id]);
            
        } catch (\Exception $mailError) {
            Log::error('Failed to send vendor registration emails', [
                'error' => $mailError->getMessage(),
                'user_id' => $vendor->id
            ]);
        }
        
        return redirect()->back()->with('success', 'Vendor approved successfully');
    }
    
    /**
     * Reject a vendor
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectVendor($id)
    {
        $vendor = User::where('role', 'vendor')->findOrFail($id);
        
        if ($vendor->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending vendors can be rejected');
        }
        
        $vendor->status = 'rejected';
        $vendor->save();

        // Send email notifications
        try {
            // Send welcome email to vendor
            Mail::to($vendor->email)->send(new VendorRegistered($vendor));
            
            // Send notification to admin
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminNotification(
                    'New Vendor Rejected',
                    [
                        'type' => 'vendor_registration',
                        'message' => "A new vendor has rejected: {$vendor->business_name}",
                        'details' => [
                            'vendor_name' => $vendor->name,
                            'vendor_email' => $vendor->email,
                            'business_name' => $vendor->business_name,
                            'business_address' => $vendor->business_address,
                            'phone' => $vendor->phone,
                            'gst_number' => $vendor->gst_number,
                            'status' => $vendor->status,
                            'registered_at' => $vendor->created_at->format('F j, Y g:i A'),
                        ],
                        'action_url' => route('admin.vendors.reject'),
                        'action_text' => 'Reject Vendor'
                    ]
                ));
            }
            
            // Send notification to host email
            $hostEmail = config('mail.from.address');
            if ($hostEmail && $hostEmail !== 'hello@example.com') {
                Mail::to($hostEmail)->send(new AdminNotification(
                    'New Vendor Reject Notification',
                    [
                        'type' => 'vendor_registration',
                        'message' => "New vendor Rejected: {$vendor->business_name}",
                        'details' => [
                            'vendor' => "{$vendor->name} ({$vendor->email})",
                            'business' => $vendor->business_name,
                            'phone' => $vendor->phone,
                            'status' => 'Rejected Done',
                        ]
                    ]
                ));
            }
            
            Log::info('Vendor rejection emails sent successfully', ['user_id' => $vendor->id]);
            
        } catch (\Exception $mailError) {
            Log::error('Failed to send vendor registration emails', [
                'error' => $mailError->getMessage(),
                'user_id' => $vendor->id
            ]);
        }
        
        return redirect()->route('admin.vendors.pending')->with('success', 'Vendor rejected successfully');
    }
    
    /**
     * Show vendor details
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showVendor($id)
    {
        $vendor = User::where('role', 'vendor')->findOrFail($id);
        
        return view('admin.vendors.show', compact('vendor'));
    }
} 