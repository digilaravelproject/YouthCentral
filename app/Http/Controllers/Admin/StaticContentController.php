<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaticContentController extends Controller
{
    const EVENTS_LISTING_BANNER_KEY = 'events_listing';

    /**
     * Show the single events listing featured banner (one record only)
     */
    public function manageEvent()
    {
        $banner = StaticBanner::where('key', self::EVENTS_LISTING_BANNER_KEY)->first();

        // Create record if it doesn't exist (e.g. migration not run)
        if (!$banner) {
            $banner = StaticBanner::create([
                'key' => self::EVENTS_LISTING_BANNER_KEY,
                'image_path' => null,
            ]);
        }

        return view('admin.static-content.manage-event', compact('banner'));
    }

    /**
     * Edit the events listing featured banner
     */
    public function editFeaturedBanner()
    {
        $banner = StaticBanner::where('key', self::EVENTS_LISTING_BANNER_KEY)->first();

        if (!$banner) {
            $banner = StaticBanner::create([
                'key' => self::EVENTS_LISTING_BANNER_KEY,
                'image_path' => null,
            ]);
        }

        return view('admin.static-content.edit-featured-banner', compact('banner'));
    }

    /**
     * Update the events listing featured banner
     */
    public function updateFeaturedBanner(Request $request)
    {
        $validated = $request->validate([
            'featured_banner' => 'required|image|mimes:webp,jpeg,jpg,png'
        ]);

        $banner = StaticBanner::where('key', self::EVENTS_LISTING_BANNER_KEY)->first();

        if (!$banner) {
            $banner = StaticBanner::create([
                'key' => self::EVENTS_LISTING_BANNER_KEY,
                'image_path' => null,
            ]);
        }

        try {
            if ($request->hasFile('featured_banner')) {
                if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                    Storage::disk('public')->delete($banner->image_path);
                }

                $path = $request->file('featured_banner')->store('static-banners/events-listing', 'public');
                $banner->update(['image_path' => $path]);
            }

            return redirect()->route('admin.static-content.manage-event')
                ->with('success', 'Featured banner updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update featured banner. ' . $e->getMessage());
        }
    }

    /**
     * Delete the events listing featured banner
     */
    public function deleteFeaturedBanner()
    {
        $banner = StaticBanner::where('key', self::EVENTS_LISTING_BANNER_KEY)->first();

        if (!$banner) {
            return redirect()->route('admin.static-content.manage-event')
                ->with('error', 'Banner not found.');
        }

        try {
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $banner->update(['image_path' => null]);

            return redirect()->route('admin.static-content.manage-event')
                ->with('success', 'Featured banner deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete featured banner. ' . $e->getMessage());
        }
    }
}
