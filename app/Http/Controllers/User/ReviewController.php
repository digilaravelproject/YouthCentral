<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews by the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Auth::user()->reviews()->with('business')->latest()->paginate(10);
        return view('user.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     *
     * @param  int  $businessId
     * @return \Illuminate\Http\Response
     */
    public function create($businessId)
    {
        $business = Business::findOrFail($businessId);
        
        // Check if user has already reviewed this business
        $existingReview = Review::where('user_id', Auth::id())
            ->where('business_id', $businessId)
            ->first();
        
        if ($existingReview) {
            return redirect()->route('user.reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this business. You can edit your review here.');
        }
        
        return view('user.reviews.create', compact('business'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);
        
        // Check if user has already reviewed this business
        $existingReview = Review::where('user_id', Auth::id())
            ->where('business_id', $validatedData['business_id'])
            ->first();
        
        if ($existingReview) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this business. You can edit your existing review.'
                ], 422);
            }
            
            return redirect()->route('user.reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this business. You can edit your review here.');
        }
        
        $review = new Review($validatedData);
        $review->user_id = Auth::id();
        $review->status = 'pending';
        $review->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully and is pending approval.',
                'review' => $review
            ]);
        }
        
        return redirect()->route('user.reviews.index')
            ->with('success', 'Review submitted successfully and is pending approval.');
    }

    /**
     * Display the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        return view('user.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow editing if the review is still pending
        if ($review->status !== 'pending') {
            return redirect()->route('user.reviews.show', $review->id)
                ->with('error', 'You cannot edit this review because it has already been ' . $review->status . '.');
        }
        
        return view('user.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow editing if the review is still pending
        if ($review->status !== 'pending') {
            return redirect()->route('user.reviews.show', $review->id)
                ->with('error', 'You cannot edit this review because it has already been ' . $review->status . '.');
        }
        
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        $review->update($validatedData);
        
        return redirect()->route('user.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow deletion if the review is still pending
        if ($review->status !== 'pending') {
            return redirect()->route('user.reviews.index')
                ->with('error', 'You cannot delete this review because it has already been ' . $review->status . '.');
        }
        
        $review->delete();
        
        return redirect()->route('user.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
