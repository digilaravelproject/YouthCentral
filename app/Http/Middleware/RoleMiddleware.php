<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('login');
        }
        
        // Allow multiple roles (comma-separated)
        $roles = explode(',', $role);
        
        // Check if the user has any of the required roles
        foreach ($roles as $r) {
            if (Auth::user()->hasRole(trim($r))) {
                return $next($request);
            }
        }
        
        // If user does not have any of the required roles
        abort(403, 'Unauthorized action.');
    }
} 