<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorHasActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is authenticated, is a vendor, and does NOT have an active subscription
        if ($user && $user->role === 'vendor') {
            // Assuming an 'activeSubscription' method/relationship exists on the User model
            // This method should check for a subscription that is 'active' and 'ends_at' > now()
            $activeSubscription = null;
            if (method_exists($user, 'activeSubscription')) {
                $activeSubscription = $user->activeSubscription();
            }
            
            if (!$activeSubscription) {
                // If vendor doesn't have an active subscription, redirect them
                return redirect()->route('vendor.subscription.required')
                         ->with('warning', 'You need an active subscription to access this page. Please purchase a plan.');
            }
        }

        // If user is not a vendor or has an active subscription, continue
        return $next($request);
    }
}

