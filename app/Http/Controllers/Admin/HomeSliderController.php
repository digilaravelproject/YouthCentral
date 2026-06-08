<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSliderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSliderController extends Controller
{
    public function index()
    {
        $items = HomeSliderItem::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.static-content.home-slider.index', compact('items'));
    }

    public function create()
    {
        // Default sort order = last + 1
        $nextOrder = (int) HomeSliderItem::max('sort_order') + 1;
        return view('admin.static-content.home-slider.create', compact('nextOrder'));
    }

    public function edit(HomeSliderItem $item)
    {
        return view('admin.static-content.home-slider.edit', compact('item'));
    }

    public function update(Request $request, HomeSliderItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
            'link_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'image' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Delete old stored image (only if it is in public disk and not an assets path)
            if ($item->image_path && !str_starts_with($item->image_path, 'assets_') && Storage::disk('public')->exists($item->image_path)) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('home-slider', 'public');
        }

        unset($validated['image']);

        $item->update($validated);

        return redirect()->route('admin.static-content.home-slider.index')
            ->with('success', 'Slider item updated successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
            'link_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'image' => 'nullable|image|mimes:webp,jpeg,jpg,png|max:5120',
        ]);

        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (int) HomeSliderItem::max('sort_order') + 1;
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('home-slider', 'public');
        }

        unset($validated['image']);

        HomeSliderItem::create($validated + ['is_active' => true]);

        return redirect()->route('admin.static-content.home-slider.index')
            ->with('success', 'Slider item created successfully.');
    }

    public function toggleStatus(HomeSliderItem $item)
    {
        $item->update(['is_active' => !$item->is_active]);

        return redirect()->route('admin.static-content.home-slider.index')
            ->with('success', 'Slider item status updated.');
    }

    public function destroy(HomeSliderItem $item)
    {
        // Delete stored image if any (not assets path)
        if ($item->image_path && !str_starts_with($item->image_path, 'assets_') && Storage::disk('public')->exists($item->image_path)) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return redirect()->route('admin.static-content.home-slider.index')
            ->with('success', 'Slider item deleted successfully.');
    }
}

