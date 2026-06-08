<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Show the user's profile form.
     */
    public function show(Request $request): View
    {
        // You might want different views based on role,
        // or a single view that adapts.
        // For now, let's assume a single profile view.
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'numeric', 'digits_between:10,15'],
            'location' => ['nullable', 'string', 'max:255'],
            'about_me' => ['nullable', 'string', 'max:1000'],
        ]);
        
        // Additional validation for vendors
        if ($user->isVendor()) {
            $vendorValidated = $request->validate([
                'business_name' => ['required', 'string', 'max:255'],
                'business_address' => ['required', 'string', 'max:255'],
                'gst_number' => ['nullable', 'string', 'max:15'],
            ]);
            
            $validated = array_merge($validated, $vendorValidated);
        }
        
        $user->update($validated);
        
        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show form to update password
     */
    public function editPassword(): View
    {
        return view('profile.edit_password');
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }

    // Add methods for updating profile, password, etc. later
    // public function update(ProfileUpdateRequest $request): RedirectResponse { ... }
    // public function destroy(Request $request): RedirectResponse { ... }

} 