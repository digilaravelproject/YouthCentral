<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subcategories = Subcategory::with('category')->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $subcategories->items(),
                'pagination' => [
                    'current_page' => $subcategories->currentPage(),
                    'last_page' => $subcategories->lastPage(),
                    'has_more' => $subcategories->hasMorePages(),
                    'total' => $subcategories->total()
                ]
            ]);
        }
        
        return view('admin.subcategory.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategory.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:webp|max:3096',
            'icon_class' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->name),
            'icon_class' => $request->icon_class,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/subcategories'), $filename);
            $data['image'] = 'uploads/subcategories/' . $filename;
        }

        Subcategory::create($data);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory created successfully');
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
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('admin.subcategory.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:webp|max:3096',
            'icon_class' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->name),
            'icon_class' => $request->icon_class,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subcategory->image && file_exists(public_path($subcategory->image))) {
                unlink(public_path($subcategory->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/subcategories'), $filename);
            $data['image'] = 'uploads/subcategories/' . $filename;
        }

        $subcategory->update($data);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        // Here you might want to check if any businesses reference this subcategory
        // and prevent deletion if needed.
        
        // Delete image if exists
        if ($subcategory->image && file_exists(public_path($subcategory->image))) {
            unlink(public_path($subcategory->image));
        }
        
        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully');
    }
}
