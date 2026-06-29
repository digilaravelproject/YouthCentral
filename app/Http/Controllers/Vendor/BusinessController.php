<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $query = Business::where('claimed_by', Auth::id())
            ->with(['subcategory.category', 'area.city.state']);
        
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
        
        $businesses = $query->paginate(10);
        $subcategories = Subcategory::with('category')->orderBy('name', 'asc')->get();
        $selectedArea = null;
        if ($request->filled('area')) {
            $selectedArea = Area::with('city.state')->find($request->area);
        }
        
        return view('vendor.business.index', compact('businesses', 'subcategories', 'selectedArea'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with(['subcategories' => function($q) {
            $q->orderBy('name', 'asc');
        }])->get();
        $states = State::orderBy('name')->get();
        
        return view('vendor.business.create', compact('categories', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get validation rules including basic business details
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'street_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'subcategory_id' => 'required|exists:subcategories,id',
            'area_id' => 'required|exists:areas,id',
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // 1MB limit for logo
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB limit per gallery image
        ]);
        
        // Prepare data, including claimed_by and status
        $data = $validatedData; // Use validated data
        $data['claimed_by'] = Auth::id();
        $data['status'] = 'pending'; // New businesses start as pending
        $data['slug'] = Str::slug($data['business_name']);

        // Convert boolean checkbox values (handle if checkbox not checked)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            $data[$day . '_closed'] = $request->has($day . '_closed');
            // If closed, nullify open/close times
            if ($data[$day . '_closed']) {
                 $data[$day . '_open'] = null;
                 $data[$day . '_close'] = null;
            }
        }
        
        // Remove gallery_images from data array as it's not a business field
        if (array_key_exists('gallery_images', $data)) {
            unset($data['gallery_images']);
        }
        
        // Remove logo from data array as we'll handle it separately
        if (array_key_exists('logo', $data)) {
            unset($data['logo']);
        }
        
        $business = Business::create($data);
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('business/' . $business->id . '/logo', 'public');
            $business->logo_path = $logoPath;
            $business->save();
        }
        
        // Handle gallery images upload if provided
        if ($request->hasFile('gallery_images')) {
            // Check subscription limits
            $user = Auth::user();
            $activeSubscription = $user->activeSubscription();
            $plan = $activeSubscription ? $activeSubscription->plan : null;
            $maxImages = $plan ? $plan->max_images : 10;
            
            $galleryImages = $request->file('gallery_images');
            $uploadedCount = 0;
            
            foreach ($galleryImages as $image) {
                // Stop uploading if we've reached the plan limit
                if ($uploadedCount >= $maxImages) {
                    break;
                }
                
                $path = $image->store('business/' . $business->id . '/gallery', 'public');
                
                // First image is primary
                $isPrimary = ($uploadedCount === 0);
                
                $business->images()->create([
                    'path' => $path,
                    'is_primary' => $isPrimary
                ]);
                
                $uploadedCount++;
            }
            
            // Notify if some images weren't uploaded due to plan limits
            if (count($galleryImages) > $maxImages) {
                session()->flash('warning', 'Only ' . $maxImages . ' images were uploaded due to your plan limits.');
            }
        }

        try {

            $vendorData = auth()->user();

            Mail::to($vendorData->email)->send(
                new VendorBusinessCreatedNotification(
                    'Your Business Has Been Created Successfully',
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

        } catch (\Throwable $mailError) {

            Log::error('Failed to send business creation email', [
                'error'       => $mailError->getMessage(),
                'business_id' => $business->id ?? null,
            ]);
        }

        return redirect()->route('vendor.businesses.index')
            ->with('success', 'Business created successfully and pending approval');
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        // Check if the business belongs to the vendor
        if (Auth::id() !== $business->claimed_by) {
            return redirect()->route('vendor.businesses.index')
                ->with('error', 'You do not have permission to view this business');
        }
        
        $business->load(['subcategory.category', 'area.city.state']);
        
        return view('vendor.business.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        // Check if the business belongs to the vendor
        if (Auth::id() !== $business->claimed_by) {
            return redirect()->route('vendor.businesses.index')
                ->with('error', 'You do not have permission to edit this business');
        }
        
        $categories = Category::with(['subcategories' => function($q) {
            $q->orderBy('name', 'asc');
        }])->get();
        $states = State::orderBy('name')->get();
        
        $currentStateId = $business->area->city->state_id ?? null;
        $currentCityId = $business->area->city_id ?? null;
        
        $cities = $currentStateId ? City::where('state_id', $currentStateId)->orderBy('name')->get() : collect();
        $areas = $currentCityId ? Area::where('city_id', $currentCityId)->orderBy('name')->get() : collect();
        
        return view('vendor.business.edit', compact('business', 'categories', 'states', 'cities', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        // Check if the business belongs to the vendor
        if (Auth::id() !== $business->claimed_by) {
            return redirect()->route('vendor.businesses.index')
                ->with('error', 'You do not have permission to update this business');
        }
        
        // Handle standalone logo deletion if requested (before validation)
        if ($request->has('delete_logo') && !$request->has('business_name')) {
            if ($business->logo_path) {
                Storage::disk('public')->delete($business->logo_path);
                $business->logo_path = null;
                $business->save();
            }
            return redirect()->back()->with('success', 'Logo deleted successfully');
        }
        
        // Validate basic business fields
        $validatedFields = [
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'street_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'subcategory_id' => 'required|exists:subcategories,id',
            'area_id' => 'required|exists:areas,id',
        ];
        
        // Add logo validation if a logo is being uploaded
        if ($request->hasFile('logo')) {
            $validatedFields['logo'] = 'image|mimes:jpeg,png,jpg|max:1024'; // 1MB limit
        }

        // Add gallery images validation if gallery images are being uploaded
        if ($request->hasFile('gallery_images')) {
            $validatedFields['gallery_images.*'] = 'image|mimes:jpeg,png,jpg|max:2048'; // 2MB limit
        }
        
        $request->validate($validatedFields);
        
        // Handle logo deletion if requested
        if ($request->has('delete_logo') && $business->logo_path) {
            // Delete the file from storage
            Storage::disk('public')->delete($business->logo_path);
            
            // Remove the path from the database
            $business->logo_path = null;
            $business->save();
            
            // If it's a standalone action (e.g. from hidden delete form)
            if (!$request->has('business_name')) {
                return redirect()->back()->with('success', 'Logo deleted successfully');
            }
        }
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($business->logo_path) {
                Storage::disk('public')->delete($business->logo_path);
            }
            
            // Store the new logo
            $path = $request->file('logo')->store('business/' . $business->id . '/logo', 'public');
            $business->logo_path = $path;
            
            // If it's a standalone action (not details form)
            if (!$request->has('business_name')) {
                $business->save();
                return redirect()->back()->with('success', 'Logo uploaded successfully');
            }
        }

        // Handle gallery images upload if provided via main form
        if ($request->hasFile('gallery_images')) {
            $user = Auth::user();
            $activeSubscription = $user->activeSubscription();
            $plan = $activeSubscription ? $activeSubscription->plan : null;
            $maxImages = $plan ? $plan->max_images : 10;
            
            $currentImageCount = $business->images()->count();
            $galleryImages = $request->file('gallery_images');
            
            foreach ($galleryImages as $image) {
                if ($currentImageCount >= $maxImages) {
                    session()->flash('warning', 'Some images were not uploaded because you reached the limit of your plan.');
                    break;
                }
                
                $path = $image->store('business/' . $business->id . '/gallery', 'public');
                $business->images()->create([
                    'path' => $path,
                    'is_primary' => ($currentImageCount === 0)
                ]);
                $currentImageCount++;
            }
        }
        
        // Updates to pending/active businesses
        $data = $request->all();
        if ($business->status === 'active') {
            $data['status'] = 'pending';
        }
        
        // Convert boolean checkbox values (handle if checkbox not checked)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            $data[$day . '_closed'] = $request->has($day . '_closed');
            // If closed, nullify open/close times
            if ($data[$day . '_closed']) {
                 $data[$day . '_open'] = null;
                 $data[$day . '_close'] = null;
            } else {
                 $data[$day . '_open'] = $request->input($day . '_open');
                 $data[$day . '_close'] = $request->input($day . '_close');
            }
        }
        
        $business->update($data);
        
        return redirect()->route('vendor.businesses.index')
            ->with('success', 'Business updated successfully and pending approval');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        // Check if the business belongs to the vendor
        $this->authorize('delete', $business);
        
        $business->delete();
        
        return redirect()->route('vendor.businesses.index')
            ->with('success', 'Business deleted successfully');
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
     * Upload an image for a business
     */
    public function uploadImage(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        
        // Check if this business belongs to the authenticated vendor
        if($business->claimed_by !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to upload images for this business.');
        }
        
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Check if the vendor's plan allows more images
        $user = Auth::user();
        $activeSubscription = $user->activeSubscription();
        $plan = $activeSubscription ? $activeSubscription->plan : null;
        $maxImages = $plan ? $plan->max_images : 10;
        
        // Count current images
        $currentImageCount = $business->images()->count();
        
        if ($currentImageCount >= $maxImages) {
            return redirect()->back()->with('error', 'You have reached the maximum number of images allowed by your subscription plan.');
        }
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('business/' . $business->id, 'public');
            
            // If this is the first image, make it primary
            $isPrimary = $currentImageCount === 0;
            
            $business->images()->create([
                'path' => $path,
                'is_primary' => $isPrimary
            ]);
            
            return redirect()->back()->with('success', 'Image uploaded successfully.');
        }
        
        return redirect()->back()->with('error', 'Failed to upload image.');
    }
    
    /**
     * Set an image as the primary image for a business
     */
    public function setPrimaryImage(Request $request, $id)
    {
        $image = BusinessImage::findOrFail($id);
        $business = $image->business;
        
        // Check if this business belongs to the authenticated vendor
        if($business->claimed_by !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to modify this business.');
        }
        
        // Reset all images to non-primary
        $business->images()->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->update(['is_primary' => true]);
        
        return redirect()->back()->with('success', 'Primary image updated successfully.');
    }
    
    /**
     * Delete an image from a business
     */
    public function deleteImage($id)
    {
        $image = BusinessImage::findOrFail($id);
        $business = $image->business;
        
        // Check if this business belongs to the authenticated vendor
        if($business->claimed_by !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete images for this business.');
        }
        
        // Get the image path to delete the file
        $imagePath = $image->path;
        
        // Check if this is the primary image
        $isPrimary = $image->is_primary;
        
        // Delete the image record
        $image->delete();
        
        // Delete the file
        if($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
        
        // If this was the primary image, set a new primary image if one exists
        if($isPrimary) {
            $newPrimaryImage = $business->images()->first();
            if($newPrimaryImage) {
                $newPrimaryImage->update(['is_primary' => true]);
            }
        }
        
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function searchAreas(Request $request)
    {
        $q = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 50;
        
        $query = Area::with('city.state')->orderBy('name', 'asc');
        
        if (!empty($q)) {
            $query->where('name', 'like', "%{$q}%");
        }
        
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        
        $results = [];
        foreach ($paginator->items() as $area) {
            $cityName = $area->city ? $area->city->name : '';
            $stateName = ($area->city && $area->city->state) ? $area->city->state->name : '';
            $fullName = $area->name . ($cityName ? " ({$cityName}, {$stateName})" : '');
            $results[] = [
                'id' => $area->id,
                'text' => $fullName
            ];
        }
        
        return response()->json([
            'results' => $results,
            'more' => $paginator->hasMorePages()
        ]);
    }
}
