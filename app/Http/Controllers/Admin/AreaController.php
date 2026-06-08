<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $areas = Area::with(['city', 'city.state'])->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $areas->items(),
                'pagination' => [
                    'current_page' => $areas->currentPage(),
                    'last_page' => $areas->lastPage(),
                    'has_more' => $areas->hasMorePages(),
                    'total' => $areas->total()
                ]
            ]);
        }
        
        return view('admin.location.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::with('state')->get();
        return view('admin.location.areas.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        Area::create([
            'name' => $request->name,
            'city_id' => $request->city_id,
        ]);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        $cities = City::with('state')->get();
        return view('admin.location.areas.edit', compact('area', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        $area->update([
            'name' => $request->name,
            'city_id' => $request->city_id,
        ]);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        // Here you might want to check if any businesses reference this area
        // and prevent deletion if needed.
        
        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area deleted successfully');
    }
}
