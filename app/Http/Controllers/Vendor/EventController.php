<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBanner;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Mail\VendorEventCreatedNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;
use App\Mail\EventRegistrationSuccess;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('created_by', auth()->id())
            ->with(['approver'])
            ->latest()
            ->paginate(10);
        return view('vendor.events.index', compact('events'));
    }

    public function create()
    {
        // Vendors can only create General Events
        return view('vendor.events.create');
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
            'registration_amount' => 'nullable',
            // No registration_amount validation for Vendors
            'banner_images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:4096',
            'featured_banner' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $eventData = $validated;
            $eventData['created_by'] = auth()->id();
            $eventData['status'] = 'pending'; // Vendor-created events need approval
            $eventData['category'] = 'general'; // Force category to general
            
            // Remove the featured_banner file object from validated data if it exists
            if (isset($eventData['featured_banner']) && is_object($eventData['featured_banner'])) {
                unset($eventData['featured_banner']);
            }
            
            // Handle featured banner upload
            if ($request->hasFile('featured_banner')) {
                $featuredBannerPath = $request->file('featured_banner')->store('events/featured-banners', 'public');
                $eventData['featured_banner'] = $featuredBannerPath;
            }
            // $eventData['registration_amount'] = null; // General events are free

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
                // Send notification emails
            $vendorData = auth()->user();

            try {

                /*
                |--------------------------------------------------------------------------
                | 1. Send confirmation email to Vendor
                |--------------------------------------------------------------------------
                */
                Mail::to($vendorData->email)->send(
                    new VendorEventCreatedNotification(
                        'Your Event Has Been Created Successfully',
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

            } catch (\Throwable $mailError) {

                Log::error('Failed to send event creation emails', [
                    'error'    => $mailError->getMessage(),
                    'event_id' => $event->id,
                ]);
            }

            DB::commit();
            return redirect()->route('vendor.events.index')
                ->with('success', 'General Event created successfully and is pending admin approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor event creation failed: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'Failed to create event. Please check logs.')->withInput();
        }
    }

    public function edit(Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure vendor can only edit general events they created
        if ($event->category !== 'general') {
            return redirect()->route('vendor.events.index')
                ->with('error', 'You can only manage General Events.');
        }

        if (!$event->isPending() && !$event->isApproved()) { // Allow editing if pending or approved
            return redirect()->route('vendor.events.index')
                ->with('error', 'Only pending or approved events can be edited.');
        }

        return view('vendor.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure vendor can only edit general events they created
        if ($event->category !== 'general') {
            return redirect()->route('vendor.events.index')
                ->with('error', 'You can only manage General Events.');
        }

        if (!$event->isPending() && !$event->isApproved()) { // Allow editing if pending or approved
            return redirect()->route('vendor.events.index')
                ->with('error', 'Only pending or approved events can be edited.');
        }

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
            'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:4096',
            'featured_banner' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $updateData = $validated;
            $updateData['category'] = 'general'; // Keep category as general
            
            // Handle featured banner upload
            if ($request->hasFile('featured_banner')) {
                // Delete old featured banner if exists
                if ($event->featured_banner && Storage::disk('public')->exists($event->featured_banner)) {
                    Storage::disk('public')->delete($event->featured_banner);
                }
                $featuredBannerPath = $request->file('featured_banner')->store('events/featured-banners', 'public');
                $updateData['featured_banner'] = $featuredBannerPath;
            }
            // $updateData['registration_amount'] = null; // Keep it free
            // If the event was approved, changing it puts it back to pending
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
            return redirect()->route('vendor.events.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor event update failed: ' . $e->getMessage(), ['event_id' => $event->id, 'request' => $request->all()]);
            return back()->with('error', 'Failed to update event. Please check logs.')->withInput();
        }
    }

    public function destroy(Event $event)
    {
        // Use policy authorization - now only allows deletion of approved events
        if (Gate::denies('delete', $event)) {
            return redirect()->route('vendor.events.index')
                ->with('error', 'You can only delete approved events that you created.');
        }

        DB::beginTransaction();
        try {
            // Delete banner images from storage
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
            $event->delete();
            DB::commit();
            return redirect()->route('vendor.events.index')
                ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor event deletion failed: ' . $e->getMessage(), ['event_id' => $event->id]);
            return back()->with('error', 'Failed to delete event. Please check logs.');
        }
    }

    public function participants(Event $event)
    {
        // Check if the event belongs to the vendor
        if ($event->vendor_id !== auth()->id()) {
            abort(403);
        }

        $registrations = $event->registrations()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('vendor.events.participants', compact('event', 'registrations'));
    }

    /**
     * Display the registrations for a specific event created by the vendor.
     */
    public function registrations(Event $event)
    {
        // Authorization: Ensure the vendor owns this event
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $registrations = $event->registrations()
            // ->with('user') // Eager load user if needed, check for null user_id
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('vendor.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Display the details of a specific registration for an event created by the vendor.
     */
    public function showRegistration(Request $request, Event $event, EventRegistration $registration)
    {
        // Authorization: Ensure the vendor owns this event
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the registration belongs to the specified event
        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found for this event.');
        }

        // Load related data if needed (like user, payment details etc.)
        $registration->load('event'); // Load event details if needed in the view
        // $registration->load('user'); // Load user details if needed

        return view('vendor.events.registration-detail', compact('event', 'registration'));
    }

    /**
     * Display a listing of all event registrations.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_registrations(Request $request)
    {
        $query = EventRegistration::with(['event', 'user'])
            ->whereHas('event', function ($q) {
                $q->where('created_by', auth()->id());
            })
            ->orderBy('created_at', 'desc');
            
        // Filter by event if an event_id is provided
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        
        // Filter by payment status if provided
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $registrations = $query->paginate(15);
        $events = Event::orderBy('title')->get();
        
        return view('vendor.event-registrations.index', compact('registrations', 'events'));
    }
    
    /**
     * Show the form for filtering registrations.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter()
    {
        $events = Event::orderBy('title')->get();
        return view('vendor.event-registrations.filter', compact('events'));
    }
    
    /**
     * Display details for a specific registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = EventRegistration::with(['event', 'user'])->findOrFail($id);
        return view('vendor.event-registrations.show', compact('registration'));
    }
    
    public function downloadReceipt($id)
    {
        $registration = EventRegistration::with('event')->findOrFail($id);
        
        // Ensure the registration is paid
        if ($registration->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Receipt is only available for completed payments.');
        }
        
        $data = [
            'registration' => $registration,
            'event' => $registration->event,
            'issued_date' => now()->format('M d, Y'),
            'company_name' => config('app.name'),
            'company_address' => 'Youth Central<br>By Youthcentuary Academy Pvt Ltd<br>Palm Beach Society, Sector 4 Nerul,<br>Navi Mumbai',
            'company_email' => 'info@youthcentral.co',
            'receipt_id' => 'RCPT-' . $registration->id . '-' . substr($registration->razorpay_payment_id, -6),
            'currency_symbol' => config('app.currency_symbol', '₹') // Default to ₹ if not set in config
        ];
        
        $pdf = \PDF::loadView('pdfs.event-receipt', $data)
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'defaultFont' => 'DejaVu Sans'
            ]);
        $string = $registration->event->title;
        $result = str_replace(" ", "_", $string);
        return $pdf->download($registration->first_name . '_' . $registration->last_name . '_'. $result . '.pdf');
    }
} 