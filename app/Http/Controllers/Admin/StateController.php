<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::withCount('cities')->paginate(10);
        return view('admin.location.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.location.states.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states',
        ]);

        State::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully.');
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
    public function edit(State $state)
    {
        return view('admin.location.states.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $state->id,
        ]);

        $state->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        // Check if the state has cities
        if ($state->cities()->count() > 0) {
            return redirect()->route('admin.states.index')
                ->with('error', 'Cannot delete state because it has cities.');
        }

        $state->delete();

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully');
    }
}
