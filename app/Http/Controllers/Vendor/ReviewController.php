<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for businesses owned by the vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        if (empty($businessIds)) {
            // If no businesses, return empty collection with message
            return view('vendor.reviews.index', [
                'reviews' => collect(),
                'businesses' => collect(),
                'selectedBusiness' => null,
                'status' => 'all',
                'message' => 'You do not have any businesses to manage reviews for.'
            ]);
        }
        
        // Get the selected business ID from the request or use the first business
        $selectedBusinessId = $request->query('business_id', $businessIds[0]);
        
        // Check if the selected business belongs to the vendor
        if (!in_array($selectedBusinessId, $businessIds)) {
            $selectedBusinessId = $businessIds[0];
        }
        
        // Status filter
        $status = $request->query('status', 'all');
        
        // Get reviews for the selected business with status filter
        $reviewsQuery = Review::where('business_id', $selectedBusinessId);
        
        if ($status !== 'all') {
            $reviewsQuery->where('status', $status);
        }
        
        $reviews = $reviewsQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // Get all businesses for dropdown
        $businesses = Business::whereIn('id', $businessIds)->get();
        $selectedBusiness = Business::find($selectedBusinessId);
        
        return view('vendor.reviews.index', compact('reviews', 'businesses', 'selectedBusiness', 'status'));
    }

    /**
     * Display the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        // Find the review and verify it belongs to one of the vendor's businesses
        $review = Review::whereIn('business_id', $businessIds)
            ->with(['user', 'business'])
            ->findOrFail($id);
        
        return view('vendor.reviews.show', compact('review'));
    }

    /**
     * Show the form for responding to a review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respondForm($id)
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        // Find the review and verify it belongs to one of the vendor's businesses
        $review = Review::whereIn('business_id', $businessIds)
            ->with(['user', 'business'])
            ->findOrFail($id);
        
        // Only allow responding to approved reviews
        if ($review->status !== 'approved') {
            return redirect()->route('vendor.reviews.show', $review->id)
                ->with('error', 'You can only respond to approved reviews.');
        }
        
        return view('vendor.reviews.respond', compact('review'));
    }

    /**
     * Store the vendor's response to a review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respond(Request $request, $id)
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        // Find the review and verify it belongs to one of the vendor's businesses
        $review = Review::whereIn('business_id', $businessIds)
            ->findOrFail($id);
        
        // Only allow responding to approved reviews
        if ($review->status !== 'approved') {
            return redirect()->route('vendor.reviews.show', $review->id)
                ->with('error', 'You can only respond to approved reviews.');
        }
        
        $validatedData = $request->validate([
            'vendor_response' => 'required|string|max:1000',
        ]);
        
        $review->vendor_response = $validatedData['vendor_response'];
        $review->save();
        
        return redirect()->route('vendor.reviews.show', $review->id)
            ->with('success', 'Your response has been saved successfully.');
    }

    /**
     * Update the vendor's response to a review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateResponse(Request $request, $id)
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        // Find the review and verify it belongs to one of the vendor's businesses
        $review = Review::whereIn('business_id', $businessIds)
            ->findOrFail($id);
        
        // Only allow responding to approved reviews
        if ($review->status !== 'approved') {
            return redirect()->route('vendor.reviews.show', $review->id)
                ->with('error', 'You can only respond to approved reviews.');
        }
        
        $validatedData = $request->validate([
            'vendor_response' => 'required|string|max:1000',
        ]);
        
        $review->vendor_response = $validatedData['vendor_response'];
        $review->save();
        
        return redirect()->route('vendor.reviews.show', $review->id)
            ->with('success', 'Your response has been updated successfully.');
    }

    /**
     * Remove the vendor's response to a review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteResponse($id)
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        // Find the review and verify it belongs to one of the vendor's businesses
        $review = Review::whereIn('business_id', $businessIds)
            ->findOrFail($id);
        
        $review->vendor_response = null;
        $review->save();
        
        return redirect()->route('vendor.reviews.show', $review->id)
            ->with('success', 'Your response has been removed successfully.');
    }

    /**
     * Display a dashboard with review statistics for the vendor's businesses.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Get all businesses owned by the current vendor
        $businessIds = Auth::user()->businesses()->pluck('id')->toArray();
        
        if (empty($businessIds)) {
            return view('vendor.reviews.dashboard')->with('info', 'You do not have any businesses to view review statistics for.');
        }
        
        // Get overall stats for all businesses
        $totalReviews = Review::whereIn('business_id', $businessIds)
            ->where('status', 'approved')
            ->count();
            
        $averageRating = Review::whereIn('business_id', $businessIds)
            ->where('status', 'approved')
            ->avg('rating') ?: 0;
            
        // Get stats per business
        $businessStats = Business::whereIn('id', $businessIds)
            ->withCount(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->with(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->get()
            ->map(function($business) {
                $business->average_rating = $business->reviews->avg('rating') ?: 0;
                $business->rating_distribution = [
                    1 => $business->reviews->where('rating', 1)->count(),
                    2 => $business->reviews->where('rating', 2)->count(),
                    3 => $business->reviews->where('rating', 3)->count(),
                    4 => $business->reviews->where('rating', 4)->count(),
                    5 => $business->reviews->where('rating', 5)->count(),
                ];
                return $business;
            });
            
        // Get recent reviews
        $recentReviews = Review::whereIn('business_id', $businessIds)
            ->where('status', 'approved')
            ->with(['user', 'business'])
            ->latest()
            ->limit(10)
            ->get();
        
        return view('vendor.reviews.dashboard', compact(
            'totalReviews',
            'averageRating',
            'businessStats',
            'recentReviews'
        ));
    }
}