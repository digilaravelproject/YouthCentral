<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users with filtering and search capabilities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role if provided
        if ($request->filled('role') && in_array($request->role, ['admin', 'user', 'vendor'])) {
            $query->where('role', $request->role);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('business_name', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get paginated results
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Maintain query parameters for pagination
        $users->appends($request->all());
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'has_more' => $users->hasMorePages(),
                    'total' => $users->total()
                ]
            ]);
        }
        
        // Count of each user type for summary
        $userCounts = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'user' => User::where('role', 'user')->count(),
            'vendor' => User::where('role', 'vendor')->count(),
        ];
        
        return view('admin.users.index', [
            'users' => $users,
            'userCounts' => $userCounts,
            'filters' => $request->only(['role', 'search']),
        ]);
    }
    
    /**
     * Display the specified user's details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }
    
    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }
    
    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'numeric', 'digits_between:10,15'],
            'location' => ['nullable', 'string', 'max:255'],
            'about_me' => ['nullable', 'string', 'max:1000'],
            'role' => ['required', 'in:admin,user,vendor'],
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);
        
        // Additional validation for vendors
        if ($validated['role'] === 'vendor') {
            $vendorValidated = $request->validate([
                'business_name' => ['required', 'string', 'max:255'],
                'business_address' => ['required', 'string', 'max:255'],
                'gst_number' => ['nullable', 'string', 'max:15'],
            ]);
            
            $validated = array_merge($validated, $vendorValidated);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    
    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Check if user has related records before deletion
        // This would be customized based on your application's relationships
        
        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'User could not be deleted. They may have related records.');
        }
    }
} 