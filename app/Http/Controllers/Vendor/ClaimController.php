<?php

namespace App\Http\Controllers\Vendor;

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
        $this->middleware('role:vendor');
    }

    /**
     * Display a listing of unclaimed businesses.
     */
    public function index(Request $request)
    {
        $query = Business::whereNull('claimed_by')
            ->with(['subcategory.category', 'area.city.state']);
        
        // Apply search filters if provided
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('subcategory')) {
            $query->inSubcategory($request->subcategory);
        }
        
        if ($request->filled('area')) {
            $query->inArea($request->area);
        }
        
        // Get businesses that don't have pending claims
        $businessesWithPendingClaims = BusinessClaim::pending()->pluck('business_id')->toArray();
        $query->whereNotIn('id', $businessesWithPendingClaims);
        
        $businesses = $query->paginate(10);
        
        return view('vendor.claims.index', compact('businesses'));
    }

    /**
     * Show form to claim a business.
     */
    public function create($businessId)
    {
        $business = Business::findOrFail($businessId);
        
        // Check if business is already claimed
        if ($business->isClaimed()) {
            return redirect()->route('vendor.claims.index')
                ->with('error', 'This business is already claimed.');
        }
        
        // Check if business already has a pending claim
        if ($business->hasPendingClaim()) {
            return redirect()->route('vendor.claims.index')
                ->with('error', 'This business already has a pending claim.');
        }
        
        return view('vendor.claims.create', compact('business'));
    }

    /**
     * Store a new claim request.
     */
    public function store(Request $request, $businessId)
    {
        $request->validate([
            'proof_description' => 'required|string|min:10',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        $business = Business::findOrFail($businessId);
        
        // Check if business is already claimed
        if ($business->isClaimed()) {
            return redirect()->route('vendor.claims.index')
                ->with('error', 'This business is already claimed.');
        }
        
        // Check if business already has a pending claim
        if ($business->hasPendingClaim()) {
            return redirect()->route('vendor.claims.index')
                ->with('error', 'This business already has a pending claim.');
        }
        
        $claim = new BusinessClaim();
        $claim->business_id = $businessId;
        $claim->user_id = Auth::id();
        $claim->proof_description = $request->proof_description;
        $claim->status = 'pending';
        
        // Handle document upload if provided
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('claim-documents', 'public');
            $claim->document_path = $path;
        }
        
        $claim->save();
        
        return redirect()->route('vendor.claims.myClaims')
            ->with('success', 'Your claim request has been submitted and is awaiting approval.');
    }

    /**
     * Display the claims made by the vendor.
     */
    public function myClaims()
    {
        $claims = BusinessClaim::where('user_id', Auth::id())
            ->with(['business', 'business.subcategory', 'business.area'])
            ->latest()
            ->paginate(10);
        
        return view('vendor.claims.my-claims', compact('claims'));
    }

    /**
     * Show a specific claim details.
     */
    public function show($claimId)
    {
        $claim = BusinessClaim::where('user_id', Auth::id())
            ->with(['business', 'business.subcategory', 'business.area'])
            ->findOrFail($claimId);
        
        return view('vendor.claims.show', compact('claim'));
    }
}
