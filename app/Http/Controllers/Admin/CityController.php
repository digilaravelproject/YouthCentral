<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cities = City::with('state')->withCount('areas')->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $cities->items(),
                'pagination' => [
                    'current_page' => $cities->currentPage(),
                    'last_page' => $cities->lastPage(),
                    'has_more' => $cities->hasMorePages(),
                    'total' => $cities->total()
                ]
            ]);
        }
        
        return view('admin.location.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::all();
        return view('admin.location.cities.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        City::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully');
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
    public function edit(City $city)
    {
        $states = State::all();
        return view('admin.location.cities.edit', compact('city', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        $city->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        // Check if the city has areas
        if ($city->areas()->count() > 0) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'Cannot delete city because it has areas.');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully');
    }
}
