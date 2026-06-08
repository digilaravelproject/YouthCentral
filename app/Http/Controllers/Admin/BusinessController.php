<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Mail\VendorBusinessCreatedNotification;
use Illuminate\Support\Facades\Mail;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Business::with(['subcategory.category', 'area.city.state', 'owner']);
        
        // Apply filters if provided
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('subcategory')) {
            $query->inSubcategory($request->subcategory);
        }
        
        if ($request->filled('area')) {
            $query->inArea($request->area);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $businesses = $query->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $businesses->items(),
                'pagination' => [
                    'current_page' => $businesses->currentPage(),
                    'last_page' => $businesses->lastPage(),
                    'has_more' => $businesses->hasMorePages(),
                    'total' => $businesses->total()
                ]
            ]);
        }
        
        $subcategories = Subcategory::with('category')->get();
        $areas = Area::with('city.state')->get();
        
        return view('admin.business.index', compact('businesses', 'subcategories', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('subcategories')->get();
        $states = State::with('cities.areas')->get();
        $vendors = User::where('role', 'vendor')->get();
        
        return view('admin.business.create', compact('categories', 'states', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'pinterest_link' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'street_address' => 'required|string',
            'zipcode' => 'nullable|string|max:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'subcategory_id' => 'required|exists:subcategories,id',
            'area_id' => 'required|exists:areas,id',
            'claimed_by' => 'nullable|exists:users,id',
            'logo' => 'nullable|image|mimes:webp|max:1024',
            'gallery_images.*' => 'nullable|image|mimes:webp|max:2048',
            // Business hours fields
            'monday_open' => 'nullable|date_format:H:i',
            'monday_close' => 'nullable|date_format:H:i|after:monday_open',
            'monday_closed' => 'nullable|boolean',
            'tuesday_open' => 'nullable|date_format:H:i',
            'tuesday_close' => 'nullable|date_format:H:i|after:tuesday_open',
            'tuesday_closed' => 'nullable|boolean',
            'wednesday_open' => 'nullable|date_format:H:i',
            'wednesday_close' => 'nullable|date_format:H:i|after:wednesday_open',
            'wednesday_closed' => 'nullable|boolean',
            'thursday_open' => 'nullable|date_format:H:i',
            'thursday_close' => 'nullable|date_format:H:i|after:thursday_open',
            'thursday_closed' => 'nullable|boolean',
            'friday_open' => 'nullable|date_format:H:i',
            'friday_close' => 'nullable|date_format:H:i|after:friday_open',
            'friday_closed' => 'nullable|boolean',
            'saturday_open' => 'nullable|date_format:H:i',
            'saturday_close' => 'nullable|date_format:H:i|after:saturday_open',
            'saturday_closed' => 'nullable|boolean',
            'sunday_open' => 'nullable|date_format:H:i',
            'sunday_close' => 'nullable|date_format:H:i|after:sunday_open',
            'sunday_closed' => 'nullable|boolean',
        ]);
        
        $data = $request->except(['logo', 'gallery_images']);
        
        // Set default status if not provided
        $data['status'] = 'active';
        
        // Convert boolean checkbox values for business hours (handle if checkbox not checked)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            $data[$day . '_closed'] = $request->has($day . '_closed');
            // If closed, nullify open/close times
            if ($data[$day . '_closed']) {
                $data[$day . '_open'] = null;
                $data[$day . '_close'] = null;
            }
        }
        
        // Create slug
        $data['slug'] = Str::slug($data['business_name']);
        
        try {
            $business = Business::create($data);
            
            // Handle logo upload if provided
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('business/' . $business->id . '/logo', 'public');
                $business->logo_path = $logoPath;
                $business->save();
            }
            
            // Handle gallery images upload if provided
            if ($request->hasFile('gallery_images')) {
                $galleryImages = $request->file('gallery_images');
                $uploadedCount = 0;
                
                foreach ($galleryImages as $image) {
                    $path = $image->store('business/' . $business->id . '/gallery', 'public');
                    
                    // First image is primary
                    $isPrimary = ($uploadedCount === 0);
                    
                    $business->images()->create([
                        'path' => $path,
                        'is_primary' => $isPrimary
                    ]);
                    
                    $uploadedCount++;
                }
            }
            
            return redirect()->route('admin.businesses.index')
                ->with('success', 'Business created successfully');
                
        } catch (\Exception $e) {
            \Log::error('Admin business creation failed: ' . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to create business. Please check the form and try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        $business->load(['subcategory.category', 'area.city.state', 'owner']);
        
        return view('admin.business.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        $categories = Category::with('subcategories')->get();
        $states = State::with('cities.areas')->get();
        $vendors = User::where('role', 'vendor')->get();
        
        return view('admin.business.edit', compact('business', 'categories', 'states', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'street_address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'subcategory_id' => 'required|exists:subcategories,id',
            'area_id' => 'required|exists:areas,id',
            'claimed_by' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,pending',
            'logo' => 'nullable|image|mimes:webp|max:1024',
            'gallery_images.*' => 'nullable|image|mimes:webp|max:2048',
            // Business hours fields
            'monday_open' => 'nullable|date_format:H:i',
            'monday_close' => 'nullable|date_format:H:i|after:monday_open',
            'monday_closed' => 'nullable|boolean',
            'tuesday_open' => 'nullable|date_format:H:i',
            'tuesday_close' => 'nullable|date_format:H:i|after:tuesday_open',
            'tuesday_closed' => 'nullable|boolean',
            'wednesday_open' => 'nullable|date_format:H:i',
            'wednesday_close' => 'nullable|date_format:H:i|after:wednesday_open',
            'wednesday_closed' => 'nullable|boolean',
            'thursday_open' => 'nullable|date_format:H:i',
            'thursday_close' => 'nullable|date_format:H:i|after:thursday_open',
            'thursday_closed' => 'nullable|boolean',
            'friday_open' => 'nullable|date_format:H:i',
            'friday_close' => 'nullable|date_format:H:i|after:friday_open',
            'friday_closed' => 'nullable|boolean',
            'saturday_open' => 'nullable|date_format:H:i',
            'saturday_close' => 'nullable|date_format:H:i|after:saturday_open',
            'saturday_closed' => 'nullable|boolean',
            'sunday_open' => 'nullable|date_format:H:i',
            'sunday_close' => 'nullable|date_format:H:i|after:sunday_open',
            'sunday_closed' => 'nullable|boolean',
        ]);
        
        $data = $request->except(['logo', 'gallery_images', 'delete_logo']);
        
        // Convert boolean checkbox values for business hours (handle if checkbox not checked)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            $data[$day . '_closed'] = $request->has($day . '_closed');
            // If closed, nullify open/close times
            if ($data[$day . '_closed']) {
                $data[$day . '_open'] = null;
                $data[$day . '_close'] = null;
            }
        }
        
        // Handle logo deletion if requested
        if ($request->has('delete_logo') && $business->logo_path) {
            Storage::disk('public')->delete($business->logo_path);
            $data['logo_path'] = null;
        }
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($business->logo_path) {
                Storage::disk('public')->delete($business->logo_path);
            }
            
            // Store the new logo
            $logoPath = $request->file('logo')->store('business/' . $business->id . '/logo', 'public');
            $data['logo_path'] = $logoPath;
        }
        
        $business->update($data);
        
        // Handle gallery images upload if provided
        if ($request->hasFile('gallery_images')) {
            $galleryImages = $request->file('gallery_images');
            
            foreach ($galleryImages as $image) {
                $path = $image->store('business/' . $business->id . '/gallery', 'public');
                
                // If no primary image exists, make this one primary
                $isPrimary = !$business->images()->where('is_primary', true)->exists();
                
                $business->images()->create([
                    'path' => $path,
                    'is_primary' => $isPrimary
                ]);
            }
        }
        
        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        $business->delete();
        
        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business deleted successfully');
    }
    
    /**
     * Unclaim a business, removing vendor association.
     */
    public function unclaim(Business $business)
    {
        $business->update(['claimed_by' => null]);
        
        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business unclaimed successfully');
    }
    
    /**
     * Get subcategories based on selected category for AJAX dropdown.
     */
    public function getSubcategories(Request $request)
    {
        $subcategories = Subcategory::where('category_id', $request->category_id)->get();
        
        return response()->json($subcategories);
    }
    
    /**
     * Get cities based on selected state for AJAX dropdown.
     */
    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();
        
        return response()->json($cities);
    }
    
    /**
     * Get areas based on selected city for AJAX dropdown.
     */
    public function getAreas(Request $request)
    {
        $areas = Area::where('city_id', $request->city_id)->get();
        
        return response()->json($areas);
    }
    
    /**
     * Display a listing of businesses pending approval.
     */
    public function pending(Request $request)
    {
        $query = Business::where('status', 'pending')
            ->with(['subcategory.category', 'area.city.state', 'owner']);

        // Apply filters if provided (optional, but good practice)
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Add other relevant filters if needed (e.g., by subcategory, area)
        
        $businesses = $query->paginate(10);
        
        // Pass any necessary data for filters to the view
        $subcategories = Subcategory::with('category')->get();
        $areas = Area::with('city.state')->get();
        
        return view('admin.business.pending', compact('businesses', 'subcategories', 'areas'));
    }
    
    /**
     * Approve a pending business.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function approve(Business $business)
    {
        $business->update(['status' => 'active']);
        
        // Notify the owner if the business is claimed
        if ($business->claimed_by) {
            // Add notification logic here if needed
        }

        try {

            $vendorData = \App\Models\User::find($business->claimed_by);
            /*
            |--------------------------------------------------------------------------
            | 1. Send confirmation email to Vendor
            |--------------------------------------------------------------------------
            */
            if (!$vendorData) {
                throw new \Exception('Vendor user not found for business ID: ' . $business->id);
            } else {
                Mail::to($vendorData->email)->send(
                    new VendorBusinessCreatedNotification(
                        'Your Business Has Been Approved Successfully',
                        [
                            'business_name'   => $business->business_name,
                            'slug'            => $business->slug,
                            'email'           => $business->email,
                            'phone'           => $business->phone,
                            'website'         => $business->website,
                            'street_address'  => $business->street_address,
                            'status'          => $business->status ?? 'pending',
                            'created_at'      => $business->created_at->format('F j, Y g:i A'),
                        ]
                    )
                );
            }

        } catch (\Throwable $mailError) {

            Log::error('Failed to send business approval email', [
                'error'       => $mailError->getMessage(),
                'business_id' => $business->id ?? null,
            ]);
        }
        
        return redirect()->route('admin.businesses.pending')
            ->with('success', 'Business has been approved successfully');
    }
    
    /**
     * Reject a pending business.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function reject(Business $business)
    {
        $business->update(['status' => 'inactive']);
        
        // Notify the owner if the business is claimed
        if ($business->claimed_by) {
            // Add notification logic here if needed
        }

        try {

            $vendorData = \App\Models\User::find($business->claimed_by);
            /*
            |--------------------------------------------------------------------------
            | 1. Send confirmation email to Vendor
            |--------------------------------------------------------------------------
            */
            if (!$vendorData) {
                throw new \Exception('Vendor user not found for business ID: ' . $business->id);
            } else {
                Mail::to($vendorData->email)->send(
                    new VendorBusinessCreatedNotification(
                        'Your Business Has Been Rejected',
                        [
                            'business_name'   => $business->business_name,
                            'slug'            => $business->slug,
                            'email'           => $business->email,
                            'phone'           => $business->phone,
                            'website'         => $business->website,
                            'street_address'  => $business->street_address,
                            'status'          => $business->status ?? 'pending',
                            'created_at'      => $business->created_at->format('F j, Y g:i A'),
                        ]
                    )
                );
            }

        } catch (\Throwable $mailError) {

            Log::error('Failed to send business rejection email', [
                'error'       => $mailError->getMessage(),
                'business_id' => $business->id ?? null,
            ]);
        }
        
        return redirect()->route('admin.businesses.pending')
            ->with('success', 'Business has been rejected');
    }
}
