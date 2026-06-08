<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    /**
     * Constructor to apply middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of pending business claims.
     */
    public function index()
    {
        $claims = BusinessClaim::pending()
            ->with(['business', 'business.subcategory', 'business.area', 'vendor'])
            ->latest()
            ->paginate(10);
        
        return view('admin.claims.index', compact('claims'));
    }

    /**
     * Show a specific claim details.
     */
    public function show($claimId)
    {
        $claim = BusinessClaim::with(['business', 'business.subcategory', 'business.area', 'vendor'])
            ->findOrFail($claimId);
        
        // Get vendor's businesses
        $vendorBusinesses = Business::where('claimed_by', $claim->vendor->id)
            ->with(['subcategory.category', 'area.city'])
            ->get();
        
        return view('admin.claims.show', compact('claim', 'vendorBusinesses'));
    }

    /**
     * Approve a business claim.
     */
    public function approve($claimId)
    {
        $claim = BusinessClaim::findOrFail($claimId);
        
        // Check if the claim is still pending
        if ($claim->status !== 'pending') {
            return redirect()->route('admin.claims.index')
                ->with('error', 'This claim has already been processed.');
        }
        
        // Get the business
        $business = $claim->business;
        
        // Update the business to mark it as claimed
        $business->claimed_by = $claim->user_id;
        $business->save();
        
        // Update the claim to mark it as approved
        $claim->status = 'approved';
        $claim->processed_by = Auth::id();
        $claim->processed_at = now();
        $claim->save();
        
        // Reject any other pending claims for this business
        BusinessClaim::where('business_id', $business->id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'admin_notes' => 'Another claim for this business was approved.',
                'processed_by' => Auth::id(),
                'processed_at' => now()
            ]);
        
        return redirect()->route('admin.claims.index')
            ->with('success', 'Claim approved successfully. The business has been assigned to the vendor.');
    }

    /**
     * Reject a business claim.
     */
    public function reject(Request $request, $claimId)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:5',
        ]);
        
        $claim = BusinessClaim::findOrFail($claimId);
        
        // Check if the claim is still pending
        if ($claim->status !== 'pending') {
            return redirect()->route('admin.claims.index')
                ->with('error', 'This claim has already been processed.');
        }
        
        // Update the claim to mark it as rejected
        $claim->status = 'rejected';
        $claim->admin_notes = $request->admin_notes;
        $claim->processed_by = Auth::id();
        $claim->processed_at = now();
        $claim->save();
        
        return redirect()->route('admin.claims.index')
            ->with('success', 'Claim rejected successfully.');
    }

    /**
     * List all processed claims (approved/rejected).
     */
    public function history()
    {
        $claims = BusinessClaim::whereIn('status', ['approved', 'rejected'])
            ->with(['business', 'business.subcategory', 'business.area', 'vendor', 'admin'])
            ->latest('processed_at')
            ->paginate(10);
        
        return view('admin.claims.history', compact('claims'));
    }
}
