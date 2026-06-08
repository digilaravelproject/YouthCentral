<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    /**
     * Display a listing of the reviews based on status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Default to 'pending' if no status is specified
        $status = $request->query('status', 'pending');
        
        $reviews = Review::with(['user', 'business'])
            ->withStatus($status)
            ->latest()
            ->paginate(10);
            
        $pendingCount = Review::pending()->count();
        $approvedCount = Review::approved()->count();
        $rejectedCount = Review::rejected()->count();
        
        return view('admin.reviews.index', compact(
            'reviews', 
            'status', 
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Display the specified review for moderation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::with(['user', 'business'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->status = 'approved';
        $review->save();
        
        // Could add notification to user here that their review was approved
        
        return redirect()->route('admin.reviews.index', ['status' => 'pending'])
            ->with('success', 'Review has been approved successfully.');
    }

    /**
     * Show the form for rejecting a review with a reason.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectForm($id)
    {
        $review = Review::with(['user', 'business'])->findOrFail($id);
        return view('admin.reviews.reject', compact('review'));
    }

    /**
     * Reject the specified review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $validatedData = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
        
        $review = Review::findOrFail($id);
        $review->status = 'rejected';
        $review->rejection_reason = $validatedData['rejection_reason'];
        $review->save();
        
        // Could add notification to user here that their review was rejected
        
        return redirect()->route('admin.reviews.index', ['status' => 'pending'])
            ->with('success', 'Review has been rejected successfully.');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        
        return redirect()->route('admin.reviews.index', ['status' => 'pending'])
            ->with('success', 'Review has been deleted successfully.');
    }

    /**
     * Display a dashboard with review statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Gather review statistics
        $stats = [
            'total_reviews' => \App\Models\Review::count(),
            'pending_reviews' => \App\Models\Review::where('status', 'pending')->count(),
            'approved_reviews' => \App\Models\Review::where('status', 'approved')->count(),
            'rejected_reviews' => \App\Models\Review::where('status', 'rejected')->count(),
            'average_rating' => \App\Models\Review::where('status', 'approved')->avg('rating') ?? 0,
            
            // Get reviews by category
            'reviews_by_category' => \App\Models\Review::where('reviews.status', 'approved')
                ->join('businesses', 'reviews.business_id', '=', 'businesses.id')
                ->join('subcategories', 'businesses.subcategory_id', '=', 'subcategories.id')
                ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                ->select('categories.name as category', \DB::raw('count(*) as count'))
                ->groupBy('categories.name')
                ->get(),
                
            // Get reviews by month for the last 6 months
            'reviews_by_month' => \App\Models\Review::where('reviews.status', 'approved')
                ->where('reviews.created_at', '>=', now()->subMonths(6))
                ->select(\DB::raw('DATE_FORMAT(reviews.created_at, "%b %Y") as month'), \DB::raw('count(*) as count'))
                ->groupBy('month')
                ->orderBy('reviews.created_at')
                ->get()
        ];
        
        return view('admin.reviews.dashboard', compact('stats'));
    }
}
