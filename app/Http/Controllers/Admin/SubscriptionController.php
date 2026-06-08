<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan']);
        
        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active', 'cancelled', 'expired'])) {
            $query->where('status', $request->status);
        }
        
        // Filter by plan
        if ($request->has('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }
        
        // Filter by search term (user email or name)
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $subscriptions = $query->latest()->paginate(10);
        $plans = Plan::all();
        
        return view('admin.subscriptions.index', compact('subscriptions', 'plans'));
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        $plans = Plan::active()->get();
        $users = User::where('role', 'vendor')->get();
        
        return view('admin.subscriptions.create', compact('plans', 'users'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'status' => ['required', Rule::in(['active', 'cancelled', 'expired'])],
            'started_at' => 'required|date',
            'ends_at' => 'nullable|date|after:started_at',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'payment_details' => 'nullable|array',
        ]);
        
        // Cancel any existing active subscriptions for this user
        $user = User::find($validated['user_id']);
        $activeSubscription = $user->activeSubscription();
        
        if ($activeSubscription) {
            $activeSubscription->status = 'cancelled';
            $activeSubscription->save();
        }
        
        $subscription = Subscription::create($validated);
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit(Subscription $subscription)
    {
        $plans = Plan::all();
        $users = User::role('vendor')->get();
        
        return view('admin.subscriptions.edit', compact('subscription', 'plans', 'users'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'status' => ['required', Rule::in(['active', 'cancelled', 'expired'])],
            'started_at' => 'required|date',
            'ends_at' => 'nullable|date|after:started_at',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'payment_details' => 'nullable|array',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified subscription from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(Subscription $subscription)
    {
        if ($subscription->status !== 'active') {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Only active subscriptions can be cancelled.');
        }
        
        $subscription->status = 'cancelled';
        $subscription->save();
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Extend the specified subscription.
     */
    public function extend(Request $request, Subscription $subscription)
    {
        $request->validate([
            'duration_type' => ['required', Rule::in(['days', 'months', 'years'])],
            'duration_value' => 'required|integer|min:1',
        ]);
        
        if ($subscription->status !== 'active') {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Only active subscriptions can be extended.');
        }
        
        $currentEndsAt = $subscription->ends_at ?? now();
        
        // Extend subscription based on duration
        switch ($request->duration_type) {
            case 'days':
                $newEndsAt = Carbon::parse($currentEndsAt)->addDays($request->duration_value);
                break;
            case 'months':
                $newEndsAt = Carbon::parse($currentEndsAt)->addMonths($request->duration_value);
                break;
            case 'years':
                $newEndsAt = Carbon::parse($currentEndsAt)->addYears($request->duration_value);
                break;
            default:
                $newEndsAt = $currentEndsAt;
        }
        
        $subscription->ends_at = $newEndsAt;
        $subscription->save();
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription extended successfully.');
    }
} 