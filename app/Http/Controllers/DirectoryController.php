<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Business;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    /**
     * Create a new controller instance.
     * Public access - no auth required
     */
    public function __construct()
    {
        // No middleware - public access
    }
    
    /**
     * Display the business directory with search and filter capabilities.
     */
    public function index(Request $request)
    {
        try {
            $query = Business::where('status', 'active')
                ->with(['subcategory.category', 'area.city.state']);
            
            // Apply search filters
            if ($request->filled('search')) {
                $query->search($request->search);
            }
            
            if ($request->filled('subcategory')) {
                $query->inSubcategory($request->subcategory);
            }
            
            if ($request->filled('area')) {
                $query->inArea($request->area);
            }
            
            // Apply sorting
            if ($request->filled('sort')) {
                $sort = $request->sort;
                if ($sort === 'name') {
                    $query->orderBy('business_name', 'asc');
                } elseif ($sort === 'newest') {
                    $query->orderBy('created_at', 'desc');
                }
            } else {
                // Default sort by name
                $query->orderBy('business_name', 'asc');
            }
            
            $businesses = $query->paginate(12)->withQueryString();
            $categories = Category::with('subcategories')->get();
            $subcategories = Subcategory::all();
            $areas = Area::with('city.state')->get();
            
            return view('directory.index', compact('businesses', 'categories', 'subcategories', 'areas'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Directory index error: ' . $e->getMessage());
            
            // Return an error view or fallback
            return view('directory.index', [
                'businesses' => collect(),
                'categories' => Category::with('subcategories')->get() ?? collect(),
                'subcategories' => collect(),
                'areas' => collect(),
                'error' => 'An error occurred while loading the directory. Please try again later.'
            ]);
        }
    }

    /**
     * Show the details for a specific business.
     */
    public function show(Business $business)
    {
        try {
            // Only allow viewing active businesses
            if ($business->status !== 'active') {
                abort(404);
            }
            
            $business->load(['subcategory.category', 'area.city.state']);
            
            // Get similar businesses in the same subcategory
            $similarBusinesses = Business::where('status', 'active')
                ->where('subcategory_id', $business->subcategory_id)
                ->where('id', '!=', $business->id)
                ->take(4)
                ->get();
            
            return view('directory.show', compact('business', 'similarBusinesses'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Directory show error: ' . $e->getMessage());
            
            // Redirect to index with error
            return redirect()->route('directory.index')
                ->with('error', 'Unable to display the requested business. Please try again later.');
        }
    }

    /**
     * Show businesses filtered by category.
     */
    public function byCategory(Category $category)
    {
        try {
            $subcategories = $category->subcategories()->pluck('id')->toArray();
            
            $businesses = Business::where('status', 'active')
                ->whereHas('subcategory', function ($query) use ($category) {
                    $query->where('category_id', $category->id);
                })
                ->with(['subcategory.category', 'area.city.state'])
                ->paginate(12)
                ->withQueryString();
            
            $categories = Category::with('subcategories')->get();
            $areas = Area::with('city.state')->get();
            
            return view('directory.index', compact('businesses', 'categories', 'areas', 'category'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Directory byCategory error: ' . $e->getMessage());
            
            // Redirect to index with error
            return redirect()->route('directory.index')
                ->with('error', 'Unable to filter by the selected category. Please try again later.');
        }
    }

    /**
     * Show businesses filtered by subcategory.
     */
    public function bySubcategory(Subcategory $subcategory)
    {
        try {
            $businesses = Business::where('status', 'active')
                ->where('subcategory_id', $subcategory->id)
                ->with(['subcategory.category', 'area.city.state'])
                ->paginate(12)
                ->withQueryString();
            
            $categories = Category::with('subcategories')->get();
            $areas = Area::with('city.state')->get();
            
            return view('directory.index', compact('businesses', 'categories', 'areas', 'subcategory'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Directory bySubcategory error: ' . $e->getMessage());
            
            // Redirect to index with error
            return redirect()->route('directory.index')
                ->with('error', 'Unable to filter by the selected subcategory. Please try again later.');
        }
    }

    /**
     * Show businesses filtered by area.
     */
    public function byArea(Area $area)
    {
        try {
            $businesses = Business::where('status', 'active')
                ->where('area_id', $area->id)
                ->with(['subcategory.category', 'area.city.state'])
                ->paginate(12)
                ->withQueryString();
            
            $categories = Category::with('subcategories')->get();
            $areas = Area::with('city.state')->get();
            
            return view('directory.index', compact('businesses', 'categories', 'areas', 'area'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Directory byArea error: ' . $e->getMessage());
            
            // Redirect to index with error
            return redirect()->route('directory.index')
                ->with('error', 'Unable to filter by the selected area. Please try again later.');
        }
    }
}
