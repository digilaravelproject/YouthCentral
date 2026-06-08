<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of available plans.
     */
    public function plans()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('priority')
            ->get();
        
        $currentSubscription = Auth::user()->activeSubscription();
        $currentPlanId = $currentSubscription ? $currentSubscription->plan_id : null;
        
        return view('vendor.subscriptions.plans', compact('plans', 'currentPlanId', 'currentSubscription'));
    }
    
    /**
     * Display the user's subscription history.
     */
    public function history()
    {
        $subscriptions = Auth::user()->subscriptions()
            ->with('plan')
            ->latest()
            ->paginate(10);
        
        $activeSubscription = Auth::user()->activeSubscription();
        
        return view('vendor.subscriptions.history', compact('subscriptions', 'activeSubscription'));
    }
    
    /**
     * Show the payment form for a plan.
     */
    public function checkout(Plan $plan)
    {
        // Check if user already has an active subscription with this plan
        $activeSubscription = Auth::user()->activeSubscription();
        if ($activeSubscription && $activeSubscription->plan_id == $plan->id) {
            return redirect()->route('vendor.subscriptions.plans')
                ->with('info', 'You are already subscribed to this plan.');
        }
        
        // Redirect to Razorpay checkout
        return redirect()->route('razorpay.checkout', $plan->id);
    }
    
    /**
     * Process the payment and create subscription.
     * (This method is now handled by RazorpayController)
     */
    public function process(Request $request, Plan $plan)
    {
        // Redirect to Razorpay checkout - this method is no longer used directly
        return redirect()->route('razorpay.checkout', $plan->id);
    }
    
    /**
     * Display subscription success page.
     */
    public function success(Subscription $subscription)
    {
        // Ensure the subscription belongs to the authenticated user
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('vendor.subscriptions.success', compact('subscription'));
    }
    
    /**
     * Cancel the current subscription.
     */
    public function cancel()
    {
        $subscription = Auth::user()->activeSubscription();
        
        if (!$subscription) {
            return redirect()->route('vendor.subscriptions.history')
                ->with('error', 'You do not have an active subscription to cancel.');
        }
        
        $subscription->status = 'cancelled';
        $subscription->save();
        
        return redirect()->route('vendor.subscriptions.history')
            ->with('success', 'Your subscription has been cancelled.');
    }
    
    /**
     * Show current subscription details.
     */
    public function current()
    {
        $subscription = Auth::user()->activeSubscription();
        
        if (!$subscription) {
            return redirect()->route('vendor.subscriptions.plans')
                ->with('info', 'You do not have an active subscription.');
        }
        
        return view('vendor.subscriptions.current', compact('subscription'));
    }

    /**
     * Show the page requiring the vendor to purchase a subscription.
     *
     * @return \Illuminate\View\View
     */
    public function showRequiredPage()
    {
        $plans = \App\Models\Plan::where('is_active', true)
                                ->orderBy('price', 'asc') // Optional: order by price
                                ->get();
                                
        return view('vendor.subscription.required', compact('plans'));
    }
} 
