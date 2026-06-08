<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'approved_reviews' => Review::where('status', 'approved')->count(),
            'rejected_reviews' => Review::where('status', 'rejected')->count(),
            'average_rating' => Review::where('status', 'approved')->avg('rating') ?? 0,
            'reviews_by_category' => Review::where('status', 'approved')
                ->join('businesses', 'reviews.business_id', '=', 'businesses.id')
                ->join('subcategories', 'businesses.subcategory_id', '=', 'subcategories.id')
                ->select('subcategories.name as category', DB::raw('COUNT(*) as count'))
                ->groupBy('subcategories.id', 'subcategories.name')
                ->get(),
            'reviews_by_month' => Review::where('status', 'approved')
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
        ];

        return view('admin.reviews.dashboard', compact('stats'));
    }
} 