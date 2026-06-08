<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Subcategory;
use App\Models\Event;
use App\Models\HomeSliderItem;
use App\Models\State;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class PublicController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get user's location from session
        $userLocation = Session::get('user_location');

        // Homepage slider items (DB-driven)
        try {
            $sliderItems = HomeSliderItem::query()
                ->active()
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } catch (\Throwable $e) {
            // If migration not run yet, don't break homepage
            $sliderItems = collect();
        }
        
        // Get recent events (limit to 6 for homepage)
        $events = Event::where('status', 'approved')
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        // Get all categories with subcategory counts for homepage display
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses')->orderBy('businesses_count', 'desc');
        }])->withCount('businesses')->orderBy('businesses_count', 'desc')->get();
        
        // Get specific subcategories for homepage display as requested
        $requestedSubcategoryNames = [
            'Football/Soccer', 'Cricket', 'Swimming', 'Coaching Classes', 'Tuitions',
            'Theatre/Acting', 'Music', 'Day Care', 'Chess', 'Table Tennis',
            'Martial Arts/Karate', 'Foundational STEM', 'Maths/Science',
            'Library', 'Pediatrician', 'Counselling', 'Painting/Sketching'
        ];
        
        $subcategories = Subcategory::whereIn('name', $requestedSubcategoryNames)
            ->orWhere('name', 'LIKE', '%Football%')
            ->orWhere('name', 'LIKE', '%Soccer%')
            ->orWhere('name', 'LIKE', '%Taekwondo%')
            ->orWhere('name', 'LIKE', '%Martial Arts%')
            ->orWhere('name', 'LIKE', '%Karate%')
            ->orWhere('name', 'LIKE', '%STEM%')
            ->orWhere('name', 'LIKE', '%Math%')
            ->orWhere('name', 'LIKE', '%Science%')
            ->orWhere('name', 'LIKE', '%Libraries%')
            ->orWhere('name', 'LIKE', '%Painting%')
            ->orWhere('name', 'LIKE', '%Sketching%')
            ->take(18)
            ->get();
            
        // If we don't have enough subcategories matching the request, fallback to all
        if ($subcategories->count() < 10) {
            $subcategories = Subcategory::take(18)->get();
        }
        
        // Get popular cities with business counts
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();
        
        // Get trending/featured businesses with advanced location-based sorting
        $trendingQuery = Business::where('status', 'active')
            ->with(['subcategory.category', 'area.city.state', 'images']);
            
        if ($userLocation) {
            $trendingQuery->byDistancePriority($userLocation);
        } else {
            // Fallback for when no location is set
            $trendingQuery->orderBy('created_at', 'desc');
        }
        
        $trendingBusinesses = $trendingQuery->take(4)->get();
        
        // Get recent businesses with advanced location-based sorting
        $recentQuery = Business::where('status', 'active')
            ->with(['subcategory.category', 'area.city.state', 'images']);
            
        if ($userLocation) {
            $recentQuery->byDistancePriority($userLocation);
        } else {
            // Fallback for when no location is set
            $recentQuery->orderBy('created_at', 'desc');
        }
        
        $recentBusinesses = $recentQuery->take(12)->get();
        
        $locationDisplayName = null;
        if ($userLocation && isset($userLocation['area_name'])) {
            $locationDisplayName = $userLocation['area_name'] . ', ' . $userLocation['city_name'];
        }
        
        return view('public.index', [
            'categories' => $categories,
            'subcategories' => $subcategories,
            'trendingBusinesses' => $trendingBusinesses,
            'recentBusinesses' => $recentBusinesses,
            'popularCities' => $popularCities,
            'events' => $events,
            'sliderItems' => $sliderItems,
            'userLocation' => $userLocation,
            'locationDisplayName' => $locationDisplayName,
        ]);
    }

    /**
     * Update user's location in the session based on provided coordinates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Find the closest area using a raw SQL query for distance calculation
        $closestArea = Area::select('areas.*',
            \DB::raw("6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))) AS distance"))
            ->setBindings([$latitude, $longitude, $latitude])
            ->orderBy('distance', 'asc')
            ->with('city.state')
            ->first();

        if ($closestArea) {
            $userLocation = [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'area_id' => $closestArea->id,
                'area_name' => $closestArea->name,
                'city_id' => $closestArea->city->id,
                'city_name' => $closestArea->city->name,
                'state_id' => $closestArea->city->state->id,
                'state_name' => $closestArea->city->state->name,
            ];
            
            Session::put('user_location', $userLocation);

            return response()->json(['success' => true, 'message' => 'Location updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Could not find a nearby location.'], 404);
    }

    /**
     * Display the subcategories for a specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function category($categorySlug)
    {
        //dd("here");
        // Manually fetch the category by slug to ensure it's a model instance
        $category = \App\Models\Category::where('slug', $categorySlug)->firstOrFail();

        // Get user's location from session
        $userLocation = Session::get('user_location');

        // Use direct Subcategory query to ensure proper models
        $subcategories = \App\Models\Subcategory::where('category_id', $category->id)
            ->withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->get();
        
        // Apply basic area-based location filtering to businesses in this category
        $businessQuery = Business::whereIn('subcategory_id', $subcategories->pluck('id'))
                            ->with('subcategory', 'area.city.state', 'images');
                            
        if ($userLocation && !empty($userLocation['area_id'])) {
            $businessQuery->where('area_id', $userLocation['area_id']);
        }
        
        $businesses = $businessQuery->paginate(12);

        // Fetch all categories for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses')->orderBy('businesses_count', 'desc');
        }])->withCount('businesses')->orderBy('businesses_count', 'desc')->get();

        // Fetch popular cities for the navbar
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();
        
        $locationDisplayName = null;
        if ($userLocation && isset($userLocation['area_name'])) {
            $locationDisplayName = $userLocation['area_name'] . ', ' . $userLocation['city_name'];
        }
        
        return view('public.category', [
            'category' => $category,
            'subcategories' => $subcategories,
            'businesses' => $businesses,
            'categories' => $categories,
            'popularCities' => $popularCities,
            'userLocation' => $userLocation,
            'locationDisplayName' => $locationDisplayName,
        ]);
    }

    /**
     * Display the businesses for a specific subcategory.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    // public function listings(Subcategory $subcategory, Request $request)
    // {
    //     // Get user's location from session
    //     $userLocation = Session::get('user_location');

    //     // Start with subcategory businesses
    //     $query = Business::where('status', 'active')
    //                     ->where('subcategory_id', $subcategory->id)
    //                     ->with(['subcategory.category', 'area.city.state', 'images']);
        
    //     // Apply additional filters if provided from request (e.g., search term, specific area filter from UI)
    //     if ($request->filled('search')) {
    //         $query->search($request->search); // Assumes 'search' is a scope on Business model
    //     }

    //     // Note: $request->area is a filter from UI, different from user's session location for sorting
    //     if ($request->filled('area')) {
    //         $query->inArea($request->area); // Assumes 'inArea' is a scope on Business model
    //     }
        
    //     // Apply sorting logic
    //     if ($userLocation) {
    //         // If user location is set, prioritize sorting by actual distance (GPS-based)
    //         $query->byDistancePriority($userLocation);
    //     } elseif ($request->filled('sort')) {
    //         // If no user location, but a specific sort order is requested
    //         $sort = $request->sort;
    //         if ($sort === 'name') {
    //             $query->orderBy('business_name', 'asc');
    //         } elseif ($sort === 'newest') {
    //             $query->orderBy('created_at', 'desc');
    //         } elseif ($sort === 'rating') {
    //             // Example: $query->orderBy('average_rating', 'desc');
    //             // For now, fallback or implement actual rating sort if available
    //             $query->orderBy('business_name', 'asc'); 
    //             } else {
    //             // Default for unknown sort parameter if no user location
    //             $query->orderBy('business_name', 'asc');
    //         }
    //     } else {
    //         // Default sort when no user location and no specific sort request
    //         $query->orderBy('business_name', 'asc');
    //     }
        
    //     $businesses = $query->paginate(12)->withQueryString();

    //     // Add distance/priority labels if location-based sorting was applied
    //     if ($userLocation) {
    //         $businesses->getCollection()->transform(function ($business) {
    //             if (isset($business->actual_distance) && $business->actual_distance !== null) {
    //                 // For businesses with GPS coordinates, show actual distance
    //                 $distance = round($business->actual_distance, 1);
    //                 $business->priority_label = $distance . ' km away';
    //                 $business->distance_km = $distance;
    //             } elseif (isset($business->location_priority)) {
    //                 // For businesses without GPS coordinates, show hierarchical priority
    //                 $priorityLabels = [
    //                     0 => 'Same Zipcode Area',
    //                     1 => 'Same Area',
    //                     2 => 'Same City',
    //                     3 => 'Same State',
    //                     4 => 'Other Location'
    //                 ];
    //                 $business->priority_label = $priorityLabels[$business->location_priority] ?? 'Unknown Priority';
    //             }
    //             return $business;
    //         });
    //     }
        
    //     // Get related subcategories from the same category
    //     $relatedSubcategories = Subcategory::where('category_id', $subcategory->category_id)
    //                                      ->where('id', '!=', $subcategory->id)
    //                                      ->withCount('businesses')
    //                                      ->orderBy('businesses_count', 'desc')
    //                                      ->take(5)
    //                                      ->get();
        
    //     // Get popular cities
    //     $popularCities = City::withCount('businesses')
    //         ->orderBy('businesses_count', 'desc')
    //         ->take(5)
    //         ->get();
        
    //     // Get categories for navbar
    //     $categories = Category::with(['subcategories' => function($query) {
    //         $query->withCount('businesses')->orderBy('businesses_count', 'desc');
    //     }])->withCount('businesses')->orderBy('businesses_count', 'desc')->get();

    //     $locationDisplayName = null;
    //     if ($userLocation && isset($userLocation['area_name'])) {
    //         $locationDisplayName = $userLocation['area_name'] . ', ' . $userLocation['city_name'];
    //     }
        
    //     return view('public.listings', [
    //         'subcategory' => $subcategory,
    //         'businesses' => $businesses,
    //         'relatedSubcategories' => $relatedSubcategories,
    //         'popularCities' => $popularCities,
    //         'categories' => $categories,
    //         'userLocation' => $userLocation,
    //         'locationDisplayName' => $locationDisplayName,
    //     ]);
    // }


    //updated code - pearlorg

    public function listings(Subcategory $subcategory, Request $request)
    {
        // Get user's location from session
        $userLocation = Session::get('user_location');
    
        // Start with subcategory businesses
        $query = Business::where('businesses.status', 'active') // Explicitly qualify status to avoid ambiguity
                        ->where('subcategory_id', $subcategory->id)
                        ->with([
                            'subcategory.category',
                            'area.city.state',
                            'images',
                            'owner.subscriptions' // Eager-load for potential accessor use
                        ])
                        ->withCount([
                            'owner as has_active_subscription' => function ($query) {
                                $query->whereHas('subscriptions', function ($q) {
                                    $q->where('status', 'active')
                                      ->where(function ($q) {
                                          $q->whereNull('ends_at')
                                            ->orWhere('ends_at', '>', now());
                                      });
                                });
                            }
                        ]);
    
        // Apply joins for subscription status in the query
        $query->leftJoin('users', 'businesses.claimed_by', '=', 'users.id')
              ->leftJoin('subscriptions', function ($join) {
                  $join->on('users.id', '=', 'subscriptions.user_id')
                       ->where('subscriptions.status', 'active')
                       ->where(function ($q) {
                           $q->whereNull('subscriptions.ends_at')
                             ->orWhere('subscriptions.ends_at', '>', now());
                       });
              });
    
        // Apply additional filters if provided from request (e.g., search term, specific area filter from UI)
        if ($request->filled('search')) {
            $query->search($request->search); // Assumes 'search' is a scope on Business model
        }
    
        if ($request->filled('area')) {
            $query->inArea($request->area); // Assumes 'inArea' is a scope on Business model
        }
    
        // Apply sorting logic
        if ($userLocation) {
            // Calculate distance (km) and priority for businesses within 70 km with active subscription
            $query->selectRaw("
                businesses.*,
                CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END as subscription_status,
                CASE 
                    WHEN businesses.latitude IS NOT NULL 
                    AND businesses.longitude IS NOT NULL 
                    THEN (6371 * acos(
                        cos(radians(?)) * 
                        cos(radians(businesses.latitude)) * 
                        cos(radians(businesses.longitude) - radians(?)) + 
                        sin(radians(?)) * 
                        sin(radians(businesses.latitude))
                    ))
                    ELSE NULL
                END as km,
                CASE 
                    WHEN businesses.latitude IS NOT NULL 
                    AND businesses.longitude IS NOT NULL 
                    AND (6371 * acos(
                        cos(radians(?)) * 
                        cos(radians(businesses.latitude)) * 
                        cos(radians(businesses.longitude) - radians(?)) + 
                        sin(radians(?)) * 
                        sin(radians(businesses.latitude))
                    )) <= 5 
                    AND subscriptions.id IS NOT NULL THEN 0
                    ELSE 1
                END as nearby_subscribed_priority
            ", [
                $userLocation['latitude'], $userLocation['longitude'], $userLocation['latitude'],
                $userLocation['latitude'], $userLocation['longitude'], $userLocation['latitude']
            ])
            ->byDistancePriority($userLocation);
    
            // Clear any existing ORDER BY clauses to avoid conflicts from scopes like byDistancePriority
            $query->reorder();
    
            // Apply the custom sorting
            $query->orderByRaw('
                nearby_subscribed_priority ASC,
                CASE 
                    WHEN nearby_subscribed_priority = 0 THEN km
                    WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL THEN km + 70
                    ELSE location_priority + 1000
                END ASC,
                CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END DESC
            ');
        } elseif ($request->filled('sort')) {
            // If no user location, but a specific sort order is requested
            $sort = $request->sort;
            if ($sort === 'name') {
                $query->orderByRaw('CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END DESC')
                      ->orderBy('business_name', 'asc');
            } elseif ($sort === 'newest') {
                $query->orderByRaw('CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END DESC')
                      ->orderBy('created_at', 'desc');
            } elseif ($sort === 'rating') {
                $query->orderByRaw('CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END DESC')
                      ->leftJoin('reviews', 'businesses.id', '=', 'reviews.business_id')
                      ->groupBy('businesses.id')
                      ->select('businesses.*', 'has_active_subscription') // Include has_active_subscription for Blade
                      ->orderByRaw('AVG(reviews.rating) DESC NULLS LAST');
            } else {
                // Default for unknown sort parameter if no user location
                $query->orderByRaw('CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END DESC')
                      ->orderBy('business_name', 'asc');
            }
        } else {
            // Default sort when no user location and no specific sort request
            $query->orderByRaw('CASE WHEN subscriptions.id IS NOT NULL THEN 1 ELSE 0 END DESC')
                  ->orderBy('business_name', 'asc');
        }
        
        $businesses = $query->paginate(12)->withQueryString();

    
        // Add distance/priority labels if location-based sorting was applied
        if ($userLocation) {
            $businesses->getCollection()->transform(function ($business) {
                if (isset($business->km) && $business->km !== null) {
                    // Use the km column directly from the query
                    $distance = round($business->km, 1);
                    $business->priority_label = $distance . ' km away';
                    $business->distance_km = $distance;
                } elseif (isset($business->location_priority)) {
                    // For businesses without GPS coordinates, show hierarchical priority
                    $priorityLabels = [
                        0 => 'Same Zipcode Area',
                        1 => 'Same Area',
                        2 => 'Same City',
                        3 => 'Same State',
                        4 => 'Other Location'
                    ];
                    $business->priority_label = $priorityLabels[$business->location_priority] ?? 'Unknown Priority';
                }
                return $business;
            });
            // ✅ Filter distance > 5 km
            // $filtered = $businesses->filter(function ($business) {
            //     return !isset($business->distance_km) || $business->distance_km <= 5;
            // });
        
            // // Reset paginator collection
            // $businesses->setCollection($filtered->values());
        }
    
        // Get related subcategories from the same category
        $relatedSubcategories = Subcategory::where('category_id', $subcategory->category_id)
                                         ->where('id', '!=', $subcategory->id)
                                         ->withCount('businesses')
                                         ->orderBy('businesses_count', 'desc')
                                         ->take(5)
                                         ->get();
    
        // Get popular cities
        $popularCities = City::withCount('businesses')
                            ->orderBy('businesses_count', 'desc')
                            ->take(5)
                            ->get();
    
        // Get categories for navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses')->orderBy('businesses_count', 'desc');
        }])->withCount('businesses')->orderBy('businesses_count', 'desc')->get();
    
        $locationDisplayName = null;
        if ($userLocation && isset($userLocation['area_name'])) {
            $locationDisplayName = $userLocation['area_name'] . ', ' . $userLocation['city_name'];
        }
        
        
        // echo'<pre>';print_r($businesses->toArray());

        // $sql = str_replace(
        //     ['?'],
        //     ['\'%s\''],
        //     $query->toSql()
        // );
        
        // $fullSql = vsprintf($sql, $query->getBindings());
        
        // dd($fullSql);
    // echo'<pre>';print_r($businesses->toArray());die;
        return view('public.listings', [
            'subcategory' => $subcategory,
            'businesses' => $businesses,
            'relatedSubcategories' => $relatedSubcategories,
            'popularCities' => $popularCities,
            'categories' => $categories,
            'userLocation' => $userLocation,
            'locationDisplayName' => $locationDisplayName,
        ]);
    }

    /**
     * Handle contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you would typically send an email or store the message
        // For now, we'll just redirect back with a success message
        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    /**
     * Load more listings for infinite scroll (AJAX endpoint)
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadMoreListings(Subcategory $subcategory, Request $request)
    {
        try {
            // Get user's location from session
            $userLocation = Session::get('user_location');

            // Start with subcategory businesses
            $query = Business::where('status', 'active')
                            ->where('subcategory_id', $subcategory->id)
                            ->with(['subcategory.category', 'area.city.state', 'images']);
            
            // Apply additional filters if provided from request
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            if ($request->filled('area')) {
                $query->inArea($request->area);
            }
            
            // Apply sorting logic (same as listings method)
            if ($userLocation) {
                $query->byDistancePriority($userLocation);
            } elseif ($request->filled('sort')) {
                $sort = $request->sort;
                if ($sort === 'name') {
                    $query->orderBy('business_name', 'asc');
                } elseif ($sort === 'newest') {
                    $query->orderBy('created_at', 'desc');
                } elseif ($sort === 'rating') {
                    $query->orderBy('business_name', 'asc'); 
                } else {
                    $query->orderBy('business_name', 'asc');
                }
            } else {
                $query->orderBy('business_name', 'asc');
            }
            
            $businesses = $query->paginate(12)->withQueryString();

            // Add distance/priority labels if location-based sorting was applied  
            if ($userLocation) {
                $businesses->getCollection()->transform(function ($business) {
                    if (isset($business->actual_distance) && $business->actual_distance !== null) {
                        // For businesses with GPS coordinates, show actual distance
                        $distance = round($business->actual_distance, 1);
                        $business->priority_label = $distance . ' km away';
                        $business->distance_km = $distance;
                    } elseif (isset($business->location_priority)) {
                        // For businesses without GPS coordinates, show hierarchical priority
                        $priorityLabels = [
                            0 => 'Same Zipcode Area',
                            1 => 'Same Area',
                            2 => 'Same City',
                            3 => 'Same State',
                            4 => 'Other Location'
                        ];
                        $business->priority_label = $priorityLabels[$business->location_priority] ?? 'Unknown Priority';
                    }
                    return $business;
                });
            }

            // Render the business cards HTML
            $html = view('public.partials.business-item-cards', [
                'businesses' => $businesses,
                'isAjaxRequest' => true
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMorePages' => $businesses->hasMorePages(),
                'currentPage' => $businesses->currentPage(),
                'lastPage' => $businesses->lastPage(),
                'total' => $businesses->total()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load more listings.',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        // Get necessary data for the navbar and search popup
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.contact', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }
    
    public function aboutYCSpark()
    {
        return view('public.about-yc-spark');
    }
    
     public function about_yc()
    {
        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function ($query) {
            $query->withCount('businesses');
        }])->get();

        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.about_yc_spark', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.about', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Display the privacy policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacyPolicy()
    {
        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.Privacy-Policy', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Display the terms of use page.
     *
     * @return \Illuminate\View\View
     */
    public function termsOfUse()
    {
        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.terms', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Display the refund policy page.
     *
     * @return \Illuminate\View\View
     */
    public function refundPolicy()
    {
        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.Refund', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Display the infringement policy page.
     *
     * @return \Illuminate\View\View
     */
    public function infringementPolicy()
    {
        // Get necessary data for the navbar
        $categories = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.Infringement', [
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Display all parent categories.
     *
     * @return \Illuminate\View\View
     */
    public function allCategories()
    {
        // Fetch all parent categories sorted by subcategories count from higher to lower
        $allParentCategories = Category::withCount(['subcategories', 'businesses'])
                                ->orderBy('subcategories_count', 'desc')
                                ->get();

        // Data for navbar/header (consistent with other public pages)
        // Also sort subcategories by business count within each category
        $categoriesForNavbar = Category::with(['subcategories' => function($query) {
            $query->withCount('businesses')->orderBy('businesses_count', 'desc');
        }])->get();
        
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.all-categories', [
            'allParentCategories' => $allParentCategories,
            'categories' => $categoriesForNavbar, // For navbar
            'popularCities' => $popularCities,    // For header search
        ]);
    }

    /**
     * Display a list of all cities.
     *
     * @return \Illuminate\View\View
     */
    public function cities()
    {
        // Get all cities, ordered alphabetically, with business counts
        $allCities = City::withCount('businesses')
                       ->orderBy('name')
                       ->get();

        // Get data needed for the navbar
        $categories = Category::with('subcategories')->get();
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.cities', [
            'allCities' => $allCities,
            'categories' => $categories,
            'popularCities' => $popularCities
        ]);
    }

    /**
     * Show businesses by subcategory with location-based priority sorting.
     */
    public function businessesBySubcategory(Request $request, $subcategorySlug)
    {
        $subcategory = Subcategory::where('slug', $subcategorySlug)->firstOrFail();
        
        // Get user's stored location for priority sorting
        $userLocation = Session::get('user_location');
        
        $query = Business::with(['subcategory', 'area.city.state', 'zipcodeModel'])
            ->where('subcategory_id', $subcategory->id)
            ->where('status', 'active');

        // Apply location-based priority sorting
        if ($userLocation) {
            $query->byZipcodePriority($userLocation);
        } else {
            $query->orderBy('business_name', 'asc');
        }

        $businesses = $query->paginate(12);
        
        return view('public.businesses-by-subcategory', compact('subcategory', 'businesses', 'userLocation'));
    }

    /**
     * Show businesses by category with location-based priority sorting.
     */
    public function businessesByCategory(Request $request, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Get user's stored location for priority sorting
        $userLocation = Session::get('user_location');
        
        $query = Business::with(['subcategory', 'area.city.state', 'zipcodeModel'])
            ->whereHas('subcategory', function($q) use ($category) {
                $q->where('category_id', $category->id);
            })
            ->where('status', 'active');

        // Apply location-based priority sorting
        if ($userLocation) {
            $query->byZipcodePriority($userLocation);
        } else {
            $query->orderBy('business_name', 'asc');
        }

        $businesses = $query->paginate(12);
        
        return view('public.businesses-by-category', compact('category', 'businesses', 'userLocation'));
    }
} 