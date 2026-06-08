<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class PublicBusinessController extends Controller
{
    /**
     * Display the business details.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        // Load necessary relationships
        $business->load([
            'subcategory.category',
            'area.city.state',
            'images',
            'reviews' => function($query) {
                $query->where('status', 'approved')->with('user');
            },
        ]);

        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        // Get similar businesses in the same subcategory
        $similarBusinesses = Business::where('subcategory_id', $business->subcategory_id)
            ->where('id', '!=', $business->id)
            ->where('status', 'active')
            ->with(['subcategory.category', 'area.city.state', 'images'])
            ->take(4)
            ->get();

        // Get all subcategories for the search section
        $subcategories = $business->subcategory->category->subcategories;

        return view('public.business-detail', [
            'business' => $business,
            'categories' => $categories,
            'popularCities' => $popularCities,
            'similarBusinesses' => $similarBusinesses,
            'subcategories' => $subcategories
        ]);
    }
}
