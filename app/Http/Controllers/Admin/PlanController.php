<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index()
    {
        $plans = Plan::orderBy('priority')->get();
        $activePlansCount = Plan::where('is_active', true)->count();
        $subscribersCount = Subscription::where('status', 'active')->count();
        
        return view('admin.plans.index', compact('plans', 'activePlansCount', 'subscribersCount'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:plans',
            'price' => 'required|numeric|min:0',
            'duration_type' => ['required', Rule::in(['monthly', 'yearly', 'one-time'])],
            'max_businesses' => 'required|integer|min:1',
            'max_images' => 'required|integer|min:1',
            'featured_listing' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'priority' => 'required|integer|min:1',
        ];
        
        // Only require duration_value for non-one-time plans
        if ($request->duration_type !== 'one-time') {
            $rules['duration_value'] = 'required|integer|min:1';
        } else {
            $rules['duration_value'] = 'nullable|integer|min:1';
        }

        $validated = $request->validate($rules);
        
        // Set duration_value to null for one-time plans
        if ($request->duration_type === 'one-time') {
            $validated['duration_value'] = null;
        }

        $plan = Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique('plans')->ignore($plan->id)],
            'price' => 'required|numeric|min:0',
            'duration_type' => ['required', Rule::in(['monthly', 'yearly', 'one-time'])],
            'max_businesses' => 'required|integer|min:1',
            'max_images' => 'required|integer|min:1',
            'featured_listing' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'priority' => 'required|integer|min:1',
        ];
        
        // Only require duration_value for non-one-time plans
        if ($request->duration_type !== 'one-time') {
            $rules['duration_value'] = 'required|integer|min:1';
        } else {
            $rules['duration_value'] = 'nullable|integer|min:1';
        }

        $validated = $request->validate($rules);
        
        // Set duration_value to null for one-time plans
        if ($request->duration_type === 'one-time') {
            $validated['duration_value'] = null;
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan)
    {
        // Check if plan has active subscriptions
        $hasActiveSubscriptions = $plan->subscriptions()->where('status', 'active')->exists();
        
        if ($hasActiveSubscriptions) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete plan with active subscriptions.');
        }
        
        try {
            DB::beginTransaction();
            
            // Change status of all subscriptions to cancelled
            $plan->subscriptions()->update(['status' => 'cancelled']);
            
            // Delete the plan
            $plan->delete();
            
            DB::commit();
            
            return redirect()->route('admin.plans.index')
                ->with('success', 'Plan deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.plans.index')
                ->with('error', 'Failed to delete plan. ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of the plan.
     */
    public function toggleStatus(Plan $plan)
    {
        $plan->is_active = !$plan->is_active;
        $plan->save();
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan status updated successfully.');
    }

    /**
     * Show the plan's subscriptions.
     */
    public function subscriptions(Plan $plan)
    {
        $subscriptions = $plan->subscriptions()
            ->with('user')
            ->latest()
            ->paginate(10);
        
        return view('admin.plans.subscriptions', compact('plan', 'subscriptions'));
    }
} 