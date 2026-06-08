<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBanner;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where('created_by', Auth::id())
            ->where('category', 'general')
            ->latest()
            ->paginate(10);
            
        return view('user.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'registration_amount' => 'nullable',
            'banner_images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:4096',
            'featured_banner' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $eventData = $validated;
            $eventData['created_by'] = auth()->id();
            $eventData['status'] = 'pending';
            $eventData['category'] = 'general';
            
            // Remove the featured_banner file object from validated data if it exists
            if (isset($eventData['featured_banner']) && is_object($eventData['featured_banner'])) {
                unset($eventData['featured_banner']);
            }
            
            // Handle featured banner upload
            if ($request->hasFile('featured_banner')) {
                $featuredBannerPath = $request->file('featured_banner')->store('events/featured-banners', 'public');
                $eventData['featured_banner'] = $featuredBannerPath;
            }
            // $eventData['registration_amount'] = null;

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
                foreach ($request->file('banner_images') as $index => $image) {
                    $path = $image->store('events/banners', 'public');
                    EventBanner::create([
                        'event_id' => $event->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'display_order' => $index
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('user.my-events.index')
                ->with('success', 'Event created successfully and is pending admin approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User event creation failed: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'Failed to create event. Please check logs.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if (Gate::denies('view', $event)) {
             return redirect()->route('user.my-events.index')
                 ->with('error', 'You do not have permission to view this event directly.');
        }
         return redirect()->route('events.show', $event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if (Gate::denies('update', $event) || $event->category !== 'general') {
             return redirect()->route('user.my-events.index')
                 ->with('error', 'You cannot edit this event.');
         }

        return view('user.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        if ($event->category !== 'general') {
             return redirect()->route('user.my-events.index')
                 ->with('error', 'You can only manage General Events.');
        }

        if (!$event->isPending() && !$event->isApproved()) {
            return redirect()->route('user.my-events.index')
                ->with('error', 'Only pending or approved events can be edited.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'seat_limit' => 'nullable|integer|min:1',
            'registration_amount' => 'nullable',
            'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:4096',
            'featured_banner' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $updateData = $validated;
            $updateData['category'] = 'general';
            
            // Handle featured banner upload
            if ($request->hasFile('featured_banner')) {
                // Delete old featured banner if exists
                if ($event->featured_banner && Storage::disk('public')->exists($event->featured_banner)) {
                    Storage::disk('public')->delete($event->featured_banner);
                }
                $featuredBannerPath = $request->file('featured_banner')->store('events/featured-banners', 'public');
                $updateData['featured_banner'] = $featuredBannerPath;
            }
            // $updateData['registration_amount'] = null;

            if ($event->isApproved()) {
                $updateData['status'] = 'pending';
                $updateData['approved_by'] = null;
                $updateData['rejection_reason'] = null;
            }

            $event->update($updateData);

            // Handle gallery images (additional_images) on update
            // handle removed gallery images
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
            $message = 'Event updated successfully.';
            if (isset($updateData['status']) && $updateData['status'] === 'pending') {
                $message .= ' It is now pending admin approval again.';
            }
            return redirect()->route('user.my-events.index')
                 ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User event update failed: ' . $e->getMessage(), ['event_id' => $event->id, 'request' => $request->all()]);
            return back()->with('error', 'Failed to update event. Please check logs.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (Gate::denies('delete', $event) || $event->category !== 'general') {
             return redirect()->route('user.my-events.index')
                 ->with('error', 'You cannot delete this event.');
        }

        DB::beginTransaction();
        try {
            foreach ($event->banners as $banner) {
                Storage::disk('public')->delete($banner->image_path);
            }
            // Delete additional gallery images
            if (!empty($event->additional_images) && is_array($event->additional_images)) {
                foreach ($event->additional_images as $img) {
                    if ($img && Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }
            $event->registrations()->delete();
            $event->delete();
            
            DB::commit();
            return redirect()->route('user.my-events.index')
                ->with('success', 'Your event has been deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User event deletion failed: ' . $e->getMessage(), ['event_id' => $event->id, 'user_id' => Auth::id()]);
            return back()->with('error', 'Failed to delete your event. Please try again.');
        }
    }

    /**
     * Display the registrations for a specific event created by the user.
     */
    public function registrations(Event $event)
    {
        // Authorization: Ensure the user owns this event and it's a general event
        if ($event->created_by !== auth()->id() || $event->category !== 'general') {
            abort(403, 'Unauthorized action or invalid event type.');
        }

        // Optional: Only allow viewing registrations for approved events?
        // if (!$event->isApproved()) {
        //     return redirect()->route('user.my-events.index')->with('error', 'You can only view registrations for approved events.');
        // }

        $registrations = $event->registrations()
            // ->with('user') // Eager load user if you need details beyond name/email
            ->orderBy('created_at', 'desc')
            ->paginate(20); // Or any desired number

        return view('user.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Display the details of a specific registration for an event created by the user.
     */
    public function showRegistration(Request $request, Event $event, EventRegistration $registration)
    {
        // Authorization: Ensure the user owns this event and it's a general event
        if ($event->created_by !== auth()->id() || $event->category !== 'general') {
            abort(403, 'Unauthorized action or invalid event type.');
        }

        // Ensure the registration belongs to the specified event
        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found for this event.');
        }
        
        // Optional: Only allow viewing registrations for approved events?
        // if (!$event->isApproved()) {
        //     return redirect()->route('user.my-events.index')->with('error', 'You can only view registrations for approved events.');
        // }

        // Load related data if needed
        $registration->load('event');

        return view('user.events.registration-detail', compact('event', 'registration'));
    }
}
