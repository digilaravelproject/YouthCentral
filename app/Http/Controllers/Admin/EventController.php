<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Mail\VendorEventCreatedNotification;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['creator', 'approver', 'banners'])->latest()->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $events->items(),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'has_more' => $events->hasMorePages(),
                    'total' => $events->total()
                ]
            ]);
        }
        
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = [
            'yc' => 'YC Event',
            'general' => 'General Event'
        ];
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'long_description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'seat_limit' => 'nullable|integer|min:1',
            'category' => ['required', Rule::in(['yc', 'general'])],
            'registration_amount' => 'nullable',
            'banner_images.*' => 'required|image|mimes:webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:4096',
            'featured_banner' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $eventData = $validated;
            unset($eventData['banner_images']);
            unset($eventData['featured_banner']);
            $eventData['created_by'] = auth()->id();
            $eventData['status'] = 'approved'; // Admin-created events are auto-approved
            $eventData['approved_by'] = auth()->id();

            // Handle featured banner upload
            if ($request->hasFile('featured_banner')) {
                $eventData['featured_banner'] = $request->file('featured_banner')->store('events/featured-banners', 'public');
            }

            $event = Event::create($eventData);

            // Handle gallery images (additional_images)
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $image->store('events/gallery', 'public');
                }
                if (!empty($galleryPaths)) {
                    $event->update(['additional_images' => $galleryPaths]);
                }
            }

            if ($request->hasFile('banner_images')) {
                \Log::info('Banner images detected in request.');
                foreach ($request->file('banner_images') as $index => $image) {
                    \Log::info('Processing image index: ' . $index, [
                        'original_name' => $image->getClientOriginalName(),
                        'is_valid' => $image->isValid()
                    ]);
                    $path = $image->store('events/banners', 'public');
                    \Log::info('Image stored.', ['path' => $path]);
                    EventBanner::create([
                        'event_id' => $event->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'display_order' => $index
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            \Log::error('Event creation failed: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'Failed to create event. Please check the logs or contact support.')->withInput();
        }
    }

    public function edit(Event $event)
    {
        $categories = [
            'yc' => 'YC Event',
            'general' => 'General Event'
        ];
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'long_description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'seat_limit' => 'nullable|integer|min:1',
            'category' => ['required', Rule::in(['yc', 'general'])],
            'registration_amount' => 'nullable',
            'banner_images.*' => 'nullable|image|mimes:webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:4096',
            'featured_banner' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $updateData = $validated;
            unset($updateData['banner_images']);
            unset($updateData['featured_banner']);

            // Handle featured banner upload
            if ($request->hasFile('featured_banner')) {
                if ($event->featured_banner && Storage::disk('public')->exists($event->featured_banner)) {
                    Storage::disk('public')->delete($event->featured_banner);
                }
                $updateData['featured_banner'] = $request->file('featured_banner')->store('events/featured-banners', 'public');
            }

            $event->update($updateData);

            // Handle gallery images (additional_images) on update
            // handle gallery removals first
            $existing = $event->additional_images ?? [];
            if ($request->filled('remove_gallery')) {
                foreach ($request->remove_gallery as $oldPath) {
                    if (($key = array_search($oldPath, $existing)) !== false) {
                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                        unset($existing[$key]);
                    }
                }
                // reindex after removals
                $existing = array_values($existing);
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $existing[] = $image->store('events/gallery', 'public');
                }
            }

            if (!empty($existing)) {
                $event->update(['additional_images' => $existing]);
            } else {
                $event->update(['additional_images' => null]);
            }

            if ($request->hasFile('banner_images')) {
                // Consider improving banner replacement logic if needed
                if ($request->boolean('replace_all_banners')) {
                    foreach ($event->banners as $banner) {
                        Storage::disk('public')->delete($banner->image_path);
                    }
                    $event->banners()->delete();
                }

                $existingBannerCount = $event->banners()->count();
                foreach ($request->file('banner_images') as $index => $image) {
                    $path = $image->store('events/banners', 'public');
                    EventBanner::create([
                        'event_id' => $event->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0 && $request->boolean('replace_all_banners'),
                        'display_order' => $existingBannerCount + $index
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            \Log::error('Event update failed: ' . $e->getMessage(), ['event_id' => $event->id, 'request' => $request->all()]);
            return back()->with('error', 'Failed to update event. Please check the logs or contact support.')->withInput();
        }
    }

    public function destroy(Event $event)
    {
        DB::beginTransaction();
        try {
            // Delete banner images from storage
            foreach ($event->banners as $banner) {
                Storage::disk('public')->delete($banner->image_path);
            }
            // Delete featured banner from storage
            if ($event->featured_banner && Storage::disk('public')->exists($event->featured_banner)) {
                Storage::disk('public')->delete($event->featured_banner);
            }
            // Delete additional gallery images
            if (!empty($event->additional_images) && is_array($event->additional_images)) {
                foreach ($event->additional_images as $img) {
                    if ($img && Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }
            $event->delete();
            DB::commit();
            return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete event. ' . $e->getMessage());
        }
    }

    public function approve(Event $event)
    {
        $event->approve(auth()->id());

        try {

            $vendorData = \App\Models\User::find($event->created_by);
            /*
            |--------------------------------------------------------------------------
            | 1. Send confirmation email to Vendor
            |--------------------------------------------------------------------------
            */
            if (!$vendorData) {
                throw new \Exception('Vendor user not found for event ID: ' . $event->id);
            } else {
                Mail::to($vendorData->email)->send(
                    new VendorEventCreatedNotification(
                        'Your Event Has Been Approved Successfully',
                        [
                            'event_title' => $event->title,
                            'event_date'  => $event->start_date->format('F j, Y g:i A'),
                            'event_type'  => $event->category,
                            'price'       => $event->registration_amount
                                                ? '₹' . number_format($event->registration_amount, 2)
                                                : 'Free',
                            'status'       => $event->status,
                        ]
                    )
                );
            }

        } catch (\Throwable $mailError) {

            Log::error('Failed to send event approved emails', [
                'error'    => $mailError->getMessage(),
                'event_id' => $event->id,
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event approved successfully.');
    }

    public function reject(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $event->reject(auth()->id(), $validated['rejection_reason']);

        try {

            $vendorData = \App\Models\User::find($event->created_by);
            /*
            |--------------------------------------------------------------------------
            | 1. Send confirmation email to Vendor
            |--------------------------------------------------------------------------
            */
            if (!$vendorData) {
                throw new \Exception('Vendor user not found for event ID: ' . $event->id);
            } else {
                Mail::to($vendorData->email)->send(
                    new VendorEventCreatedNotification(
                        'Your Event Has Been Rejected',
                        [
                            'event_title' => $event->title,
                            'event_date'  => $event->start_date->format('F j, Y g:i A'),
                            'event_type'  => $event->category,
                            'price'       => $event->registration_amount
                                                ? '₹' . number_format($event->registration_amount, 2)
                                                : 'Free',
                            'status'       => $event->status,
                        ]
                    )
                );
            }

        } catch (\Throwable $mailError) {

            Log::error('Failed to send event rejection emails', [
                'error'    => $mailError->getMessage(),
                'event_id' => $event->id,
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event rejected successfully.');
    }

    public function pending()
    {
        $events = Event::where('status', 'pending')->with(['creator'])->latest()->paginate(10);
        return view('admin.events.pending', compact('events'));
    }

    public function participants(Event $event)
    {
        $registrations = $event->registrations()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.events.participants', compact('event', 'registrations'));
    }
} 