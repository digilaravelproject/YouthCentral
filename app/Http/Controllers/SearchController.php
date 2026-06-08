<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Area;
use App\Models\City;
use App\Models\State;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // Required for checking columns
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class SearchController extends Controller
{
    /**
     * Handle the search functionality
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $searchTerm = $request->get('query', $request->get('search', ''));
            $perPage = 12;

            Log::info('Search request (index)', [
                'query' => $searchTerm,
                'city_id' => $request->get('city_id'),
                'subcategory_id' => $request->get('subcategory_id'),
                'category_id' => $request->get('category_id')
            ]);

            // Get user's location from session
            $userLocation = Session::get('user_location');

            // Start with subcategory/category/business search base
            $query = Business::where('businesses.status', 'active')
                        ->with(['subcategory.category', 'area.city.state', 'images', 'owner.subscriptions'])
                        ->withCount([
                            'owner as has_active_subscription' => function ($q) {
                                $q->whereHas('subscriptions', function ($s) {
                                    $s->where('status', 'active')
                                      ->where(function ($inner) {
                                          $inner->whereNull('ends_at')
                                                ->orWhere('ends_at', '>', now());
                                      });
                                });
                            }
                        ]);

                        // Note: we avoid joining `users`/`subscriptions` directly to prevent duplicate rows
                        // and rely on the `withCount` alias `has_active_subscription` for subscription prioritization.

            // Category / Subcategory filters (allow passing ids)
            if ($request->filled('subcategory_id')) {
                $query->where('subcategory_id', $request->subcategory_id);
            } elseif ($request->filled('category_id')) {
                $subIds = Subcategory::where('category_id', $request->category_id)->pluck('id');
                $query->whereIn('subcategory_id', $subIds);
            }

            // Area filter from UI
            if ($request->filled('area')) {
                $query->inArea($request->area);
            }

            // City filter (accepts city_id or fallback param)
            $cityId = $request->city_id ?: ($request->city ?: $request->city_id_fallback);
            if ($cityId && $cityId > 0) {
                $query->whereHas('area.city', function($q) use ($cityId) {
                    $q->where('id', $cityId);
                });
            }

            // Apply search term across business name, description, subcategory and category
            if (!empty($searchTerm)) {
                // Check if search term matches any subcategory exactly (case-insensitive)
                $subcategoryMatch = Subcategory::where('name', $searchTerm)->first();

                if ($subcategoryMatch) {
                    $query->where('businesses.subcategory_id', $subcategoryMatch->id);
                } else {
                    // Check if it matches any category exactly (case-insensitive)
                    $categoryMatch = Category::where('name', $searchTerm)->first();

                    if ($categoryMatch) {
                        $subIds = Subcategory::where('category_id', $categoryMatch->id)->pluck('id');
                        $query->whereIn('businesses.subcategory_id', $subIds);
                    } else {
                        // Otherwise, fall back to broad search
                        $query->where(function($q) use ($searchTerm) {
                            $q->where('businesses.business_name', 'like', '%' . $searchTerm . '%')
                              ->orWhere('businesses.description', 'like', '%' . $searchTerm . '%')
                              ->orWhereHas('subcategory', function($subQ) use ($searchTerm) {
                                  $subQ->where('name', 'like', '%' . $searchTerm . '%');
                              })
                              ->orWhereHas('subcategory.category', function($catQ) use ($searchTerm) {
                                  $catQ->where('name', 'like', '%' . $searchTerm . '%');
                              })
                              ->orWhereHas('area.city', function($cityQ) use ($searchTerm) {
                                  $cityQ->where('name', 'like', '%' . $searchTerm . '%');
                              });
                        });
                    }
                }
            }

            // Sorting and prioritization (match PublicController::listings behaviour)
            if ($userLocation && !empty($userLocation['latitude']) && !empty($userLocation['longitude'])) {
                $query->selectRaw(
                    "businesses.*,
                    CASE WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL THEN (6371 * acos(
                        cos(radians(?)) * cos(radians(businesses.latitude)) * cos(radians(businesses.longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(businesses.latitude))
                    )) ELSE NULL END as km,
                    CASE WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL AND (6371 * acos(
                        cos(radians(?)) * cos(radians(businesses.latitude)) * cos(radians(businesses.longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(businesses.latitude))
                    )) <= 5 THEN 0 ELSE 1 END as nearby_subscribed_priority",
                    [
                        $userLocation['latitude'], $userLocation['longitude'], $userLocation['latitude'],
                        $userLocation['latitude'], $userLocation['longitude'], $userLocation['latitude']
                    ]
                )->byDistancePriority($userLocation);

                // Clear existing order and apply custom ordering
                $query->reorder();
                $query->orderByRaw(
                    "nearby_subscribed_priority ASC,
                    CASE WHEN nearby_subscribed_priority = 0 THEN km
                         WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL THEN km + 70
                         ELSE location_priority + 1000 END ASC, has_active_subscription DESC"
                );

            } elseif ($request->filled('sort')) {
                $sort = $request->sort;
                    if ($sort === 'name') {
                      $query->orderByRaw('has_active_subscription DESC')
                          ->orderBy('businesses.business_name', 'asc');
                    } elseif ($sort === 'newest') {
                      $query->orderByRaw('has_active_subscription DESC')
                          ->orderBy('businesses.created_at', 'desc');
                    } elseif ($sort === 'rating') {
                      $query->orderByRaw('has_active_subscription DESC')
                          ->leftJoin('reviews', 'businesses.id', '=', 'reviews.business_id')
                          ->groupBy('businesses.id')
                          ->select('businesses.*', 'has_active_subscription')
                          ->orderByRaw('AVG(reviews.rating) DESC NULLS LAST');
                    } else {
                      $query->orderByRaw('has_active_subscription DESC')
                          ->orderBy('businesses.business_name', 'asc');
                    }
            } else {
                    $query->orderByRaw('has_active_subscription DESC')
                        ->orderBy('businesses.business_name', 'asc');
            }

            // Note: avoid global groupBy to keep SQL compatible with ONLY_FULL_GROUP_BY;
            // rating sort handles grouping when needed.

            // Debug: log SQL and bindings to help diagnose empty search results
            try {
                Log::debug('Search SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
            } catch (\Throwable $e) {
                Log::debug('Failed to generate SQL for debugging', ['error' => $e->getMessage()]);
            }

            // Paginate
            $businesses = $query->paginate($perPage)->withQueryString();

            // Add distance/priority labels
            if ($userLocation) {
                $businesses->getCollection()->transform(function ($business) {
                    if (isset($business->km) && $business->km !== null) {
                        $distance = round($business->km, 1);
                        $business->priority_label = $distance . ' km away';
                        $business->distance_km = $distance;
                    } elseif (isset($business->location_priority)) {
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

            // Prepare UI data
            $cities = City::orderBy('name')->get();
            $categories = Category::with(['subcategories' => function($q) {
                $q->withCount('businesses')->orderBy('businesses_count', 'desc');
            }])->get();
            $popularCities = City::withCount('businesses')->orderBy('businesses_count', 'desc')->take(5)->get();

            // AJAX / JSON response
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'businesses' => $businesses->items(),
                        'pagination' => [
                            'current_page' => $businesses->currentPage(),
                            'last_page' => $businesses->lastPage(),
                            'per_page' => $businesses->perPage(),
                            'total' => $businesses->total(),
                            'from' => $businesses->firstItem(),
                            'to' => $businesses->lastItem(),
                        ],
                        'search_info' => [
                            'query' => $searchTerm,
                            'total_results' => $businesses->total(),
                        ]
                    ]
                ]);
            }

            // Render view
            $cityId = $request->get('city_id');

            return view('public.search-results', [
                'businesses' => $businesses,
                'query' => $searchTerm,
                'cities' => $cities,
                'cityId' => $cityId,
                'categories' => $categories,
                'popularCities' => $popularCities,
            ]);

        } catch (\Exception $e) {
            Log::error('Search (index) failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            return redirect()->back()->with('error', 'Search failed. Please try again.');
        }
    }

    public function index_old(Request $request)
    {
        try {
            $query = $request->get('query', '');
            $searchType = $request->get('search_type', 'business'); // Default to business search
            $page = $request->get('page', 1);
            $perPage = 12;

            Log::info('Search request', [
                'query' => $query,
                'search_type' => $searchType,
                'city_id' => $request->get('city_id'),
                'page' => $page
            ]);

            // Handle event search if search_type is 'events'
            if ($searchType === 'events') {
                return $this->searchEvents($request, $query, $perPage);
            }

            // Default business search logic
            return $this->searchBusinesses($request, $query, $perPage);

        } catch (\Exception $e) {
            Log::error('Search failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            // For browser requests, redirect back with error
            return redirect()->back()->with('error', 'Search failed. Please try again.');
        }
    }

    /**
     * Handle event search functionality
     * 
     * @param Request $request
     * @param string $query
     * @param int $perPage
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    private function searchEvents(Request $request, string $query, int $perPage)
    {
        // Start building the event query
        $eventQuery = Event::where('status', 'approved')
            ->where('end_date', '>=', now()); // Only upcoming events

        // Apply city filter if city_id is provided and not empty/zero
        // Also check for fallback parameter for users without JavaScript
        $cityId = $request->city_id ?: $request->city_id_fallback;
        if ($cityId && $cityId > 0) {
            $eventQuery->where('city_id', $cityId);
            Log::info('Applied city filter for events', ['city_id' => $cityId, 'source' => $request->city_id ? 'main' : 'fallback']);
        }

        // Apply search filters if query is provided
        if (!empty($query)) {
            $eventQuery->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('venue', 'like', '%' . $query . '%')
                  ->orWhere('category', 'like', '%' . $query . '%');
            });
        }

        // Get user's location from session for distance-based sorting
        $userLocation = session('user_location');
        
        // Apply distance-based sorting if user location is available, otherwise sort by date
        if ($userLocation && !empty($userLocation['latitude']) && !empty($userLocation['longitude'])) {
            // Use GPS-based distance sorting when user location coordinates are available
            $eventQuery->byDistancePriority($userLocation);
        } elseif ($userLocation) {
            // Use hierarchical location sorting when only area/city/state info is available
            $eventQuery->byLocationPriority($userLocation);
        } else {
            // Default sorting by start date when no user location available
        $eventQuery->orderBy('start_date', 'asc');
        }

        // Get paginated results with banners
        $events = $eventQuery->with('banners')->paginate($perPage);

        // Get cities for dropdown
        $cities = City::orderBy('name')->get();

        // For AJAX requests, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            $response = [
                'success' => true,
                'data' => [
                    'events' => $events->items(),
                    'pagination' => [
                        'current_page' => $events->currentPage(),
                        'last_page' => $events->lastPage(),
                        'per_page' => $events->perPage(),
                        'total' => $events->total(),
                        'from' => $events->firstItem(),
                        'to' => $events->lastItem(),
                    ],
                    'search_info' => [
                        'query' => $query,
                        'total_results' => $events->total(),
                        'search_type' => 'events',
                        'city_filter' => $request->filled('city_id') ? [
                            'city_id' => $request->city_id,
                            'applied' => true
                        ] : null
                    ]
                ]
            ];

            Log::info('Event search completed (JSON)', [
                'total_results' => $events->total(),
                'current_page' => $events->currentPage(),
            ]);

            return response()->json($response);
        }

        // For browser requests, return event search results view
        $cityId = $request->get('city_id');

        Log::info('Event search completed (View)', [
            'total_results' => $events->total(),
            'current_page' => $events->currentPage(),
        ]);

        return view('public.event-search-results', [
            'events' => $events,
            'query' => $query,
            'cities' => $cities,
            'cityId' => $cityId,
        ]);
    }

    /**
     * Handle business search functionality (original logic)
     * 
     * @param Request $request
     * @param string $query
     * @param int $perPage
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    private function searchBusinesses(Request $request, string $query, int $perPage)
    {
            // Start building the business query
            $businessQuery = Business::with(['subcategory.category', 'area.city.state', 'images'])
                ->where('status', 'active');

        // Apply city filter if city_id is provided and not empty/zero
        // Also check for 'city' parameter (used by business search from homepage)
        // And 'city_id_fallback' for users without JavaScript
        $cityId = $request->city_id ?: ($request->city ?: $request->city_id_fallback);
        if ($cityId && $cityId > 0) {
            $businessQuery->whereHas('area.city', function($q) use ($cityId) {
                $q->where('id', $cityId);
            });
            
            $source = $request->city_id ? 'city_id' : ($request->city ? 'city' : 'fallback');
            Log::info('Applied city filter for business search', ['city_id' => $cityId, 'source' => $source]);
            }

            // Apply search filters if query is provided
            if (!empty($query)) {
                $businessQuery->where(function($q) use ($query) {
                    $q->where('business_name', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%')
                      ->orWhereHas('subcategory', function($subQuery) use ($query) {
                          $subQuery->where('name', 'like', '%' . $query . '%');
                      })
                      ->orWhereHas('subcategory.category', function($catQuery) use ($query) {
                          $catQuery->where('name', 'like', '%' . $query . '%');
                      });
                });
            }

            // Apply sorting - Simple alphabetical sorting (location-independent)
            $sort = $request->get('sort', 'name');
            switch ($sort) {
                case 'newest':
                    $businessQuery->orderBy('created_at', 'desc');
                    break;
                case 'name':
                default:
                    $businessQuery->orderBy('business_name', 'asc');
                    break;
            }

            // Get paginated results
            $businesses = $businessQuery->paginate($perPage);

            // Get cities and categories for the search popup and filter dropdowns
            $popularCities = City::withCount('businesses')
                ->orderBy('businesses_count', 'desc')
                ->take(5)
                ->get();
            
            $categories = Category::with(['subcategories' => function($query) {
                $query->withCount('businesses')->orderBy('businesses_count', 'desc');
            }])->get();

            // Get all cities for the filter dropdown
            $cities = City::orderBy('name')->get();

            // For AJAX requests, return JSON
            if ($request->wantsJson() || $request->ajax()) {
                // Prepare response data for JSON
                $response = [
                    'success' => true,
                    'data' => [
                        'businesses' => $businesses->items(),
                        'pagination' => [
                            'current_page' => $businesses->currentPage(),
                            'last_page' => $businesses->lastPage(),
                            'per_page' => $businesses->perPage(),
                            'total' => $businesses->total(),
                            'from' => $businesses->firstItem(),
                            'to' => $businesses->lastItem(),
                        ],
                        'search_info' => [
                            'query' => $query,
                            'total_results' => $businesses->total(),
                            'sorting_method' => 'alphabetical',
                            'city_filter' => $request->filled('city_id') ? [
                                'city_id' => $request->city_id,
                                'applied' => true
                            ] : null
                        ]
                    ]
                ];

                // Log search results for debugging
                Log::info('Search completed (JSON)', [
                    'total_results' => $businesses->total(),
                    'current_page' => $businesses->currentPage(),
                    'sorting_method' => 'alphabetical'
                ]);

                return response()->json($response);
            }

            // For browser requests, return view
            $cityId = $request->get('city_id');
            
            // Get categories and popular cities for navbar and search popup
            $categories = Category::with(['subcategories' => function($query) {
                $query->withCount('businesses');
            }])->get();
            
            $popularCities = City::withCount('businesses')
                ->orderBy('businesses_count', 'desc')
                ->take(5)
                ->get();
            
            Log::info('Search completed (View)', [
                'total_results' => $businesses->total(),
                'current_page' => $businesses->currentPage(),
                'sorting_method' => 'alphabetical'
            ]);

            return view('public.search-results', [
                'businesses' => $businesses,
                'query' => $query,
                'cities' => $cities,
                'cityId' => $cityId,
                'categories' => $categories,
                'popularCities' => $popularCities,
            ]);
    }

    /**
     * Get search suggestions based on partial query.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request)
    {
        try {
            $query = $request->get('query', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'suggestions' => []
                ]);
            }

            // Get business suggestions (no location-based sorting)
            $businesses = Business::where('status', 'active')
                ->where('business_name', 'like', '%' . $query . '%')
                ->orderBy('business_name', 'asc')
                ->limit(5)
                ->get(['business_name', 'id', 'area_id']);

            // Get category suggestions
            $categories = Category::where('name', 'like', '%' . $query . '%')
                ->orderBy('name', 'asc')
                ->limit(3)
                ->get(['id', 'name']);

            // Get subcategory suggestions
            $subcategories = Subcategory::where('name', 'like', '%' . $query . '%')
                ->orderBy('name', 'asc')
                ->limit(3)
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'suggestions' => [
                    'businesses' => $businesses,
                    'categories' => $categories,
                    'subcategories' => $subcategories
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Suggestions failed', [
                'error' => $e->getMessage(),
                'query' => $request->get('query', '')
            ]);

            return response()->json([
                'success' => false,
                'suggestions' => []
            ], 500);
        }
    }
} 