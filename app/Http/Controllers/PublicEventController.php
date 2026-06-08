<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\StaticBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        // Get user's location from session for distance-based sorting
        $userLocation = session('user_location');
        
        // Get filter parameters
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $areaId = $request->input('area_id');
        
        // Fetch approved, upcoming 'general' events for the main list
        $query = Event::where('status', 'approved')
                      ->where('category', 'general') // Only general for the main list now
                      ->where('end_date', '>=', now());
                      
        // Apply filters if provided
        if ($stateId) {
            $query->where('state_id', $stateId);
        }
        
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        
        if ($areaId) {
            $query->where('area_id', $areaId);
        }
        
        // Apply distance-based sorting if user location is available, otherwise sort by date
        if ($userLocation && !empty($userLocation['latitude']) && !empty($userLocation['longitude'])) {
            // Use GPS-based distance sorting when user location coordinates are available
            $query->byDistancePriority($userLocation);
        } elseif ($userLocation) {
            // Use hierarchical location sorting when only area/city/state info is available
            $query->byLocationPriority($userLocation);
        } else {
            // Default sorting by start date when no user location available
        $query->orderBy('start_date', 'asc');
        }

        // Paginate the results
        $generalEvents = $query->paginate(9)->appends($request->except('page'));

        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            $eventsHtml = '';
            foreach ($generalEvents as $event) {
                $eventsHtml .= '<div class="col-lg-4 col-md-6 mb-4">';
                $eventsHtml .= view('public.events.partials.event-card', ['event' => $event])->render();
                $eventsHtml .= '</div>';
            }
            
            return response()->json([
                'success' => true,
                'html' => $eventsHtml,
                'pagination' => [
                    'current_page' => $generalEvents->currentPage(),
                    'last_page' => $generalEvents->lastPage(),
                    'has_more' => $generalEvents->hasMorePages(),
                    'total' => $generalEvents->total(),
                    'per_page' => $generalEvents->perPage(),
                    'from' => $generalEvents->firstItem(),
                    'to' => $generalEvents->lastItem(),
                ]
            ]);
        }

        // Fetch the single *next* upcoming, approved 'yc' event for the hero section
        $latestYcEvent = Event::where('status', 'approved')
                              ->where('category', 'yc')
                              ->where('end_date', '>=', now())
                              ->with('banners') // Eager load banners
                              ->orderBy('start_date', 'asc') // Get the soonest one
                              ->first(); // Fetch only the first matching event

        // Fetch data needed for the navbar
        $categories = Category::with('subcategories')->get();
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            // ->take(5)
            ->get();

        // Fetch events listing featured banner (single record - displays above events listing)
        try {
            $eventsListingBanner = StaticBanner::getEventsListingBanner();
        } catch (\Exception $e) {
            $eventsListingBanner = null;
        }

        // Pass all variables to the view
        return view('public.events.index', compact(
            'generalEvents', 
            'latestYcEvent', 
            'categories', 
            'popularCities',
            'stateId',
            'cityId',
            'areaId',
            'eventsListingBanner'
        ));
    }

    public function show(Event $event)
    {
        // dd('ss');
        if ($event->status !== 'approved') {
            abort(404);
        }

        // Load event banners and ensure category is loaded
        $event->load('banners');

        // Fetch data needed for the navbar
        $categories = Category::with('subcategories')->get();
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        // Determine if registration is open (consider seat limit and end date)
        $isRegistrationOpen = true;
        if ($event->seat_limit && $event->registrations()->where('payment_status', 'completed')->count() >= $event->seat_limit) {
            $isRegistrationOpen = false;
        }
        if ($event->end_date < now()) {
            $isRegistrationOpen = false;
        }
        
        $schoolTypes = DB::table('school_types')->get(); 

        return view('public.events.show', compact('event', 'categories', 'popularCities', 'isRegistrationOpen', 'schoolTypes'));
    }

    public function result(Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }

        // Fetch data needed for the navbar
        $categories = Category::with('subcategories')->get();
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        return view('public.events.result', compact('event', 'categories', 'popularCities'));
    }

    public function register(Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }

        // Check if event has reached seat limit
        if ($event->seat_limit && $event->registrations()->count() >= $event->seat_limit) {
            return back()->with('error', 'This event has reached its maximum capacity.');
        }

        return view('public.events.register', compact('event'));
    }
    
        /**
     * Show the parent/guardian consent page for YC SPARK events
     *
     * @return \Illuminate\Http\Response
     */
    public function parentGuardianConsent()
    {
        // Fetch data needed for the navbar
        $categories = Category::with('subcategories')->get();
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();
            
        return view('public.events.parent_guardian_consent', compact('categories', 'popularCities'));
    }
    
        /**
     * Show the parent/guardian consent page for general events
     *
     * @return \Illuminate\Http\Response
     */
    public function GeneralparentGuardianConsent()
    { 
        return view('public.events.general_parent_guardian_consent');
    }
    
        public function yc_ignite_show(Event $event){

         if ($event->status !== 'approved') {
            abort(404);
        }

        // Load event banners and ensure category is loaded
        $event->load('banners');

        // Fetch data needed for the navbar
        $categories = Category::with('subcategories')->get();
        $popularCities = City::withCount('businesses')
            ->orderBy('businesses_count', 'desc')
            ->take(5)
            ->get();

        // Determine if registration is open (consider seat limit and end date)
        $isRegistrationOpen = true;
        if ($event->seat_limit && $event->registrations()->where('payment_status', 'completed')->count() >= $event->seat_limit) {
            $isRegistrationOpen = false;
        }
        if ($event->end_date < now()) {
            $isRegistrationOpen = false;
        }
        $schoolTypes = DB::table('school_types')->get(); 
        return view('public.events.yc_ignite', compact('event', 'categories', 'popularCities', 'isRegistrationOpen', 'schoolTypes'));
    }
    
    public function faqs(){
        
                return view('public.events.faqs');

    }
}