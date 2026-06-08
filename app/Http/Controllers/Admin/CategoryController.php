<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('subcategories')->paginate(10);
        
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'has_more' => $categories->hasMorePages(),
                    'total' => $categories->total()
                ]
            ]);
        }
        
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'image' => 'nullable|image|mimes:webp|max:2048',
            'icon_class' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon_class' => $request->icon_class,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/categories'), $filename);
            $data['image'] = 'uploads/categories/' . $filename;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
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
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:webp|max:2048',
            'icon_class' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon_class' => $request->icon_class,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/categories'), $filename);
            $data['image'] = 'uploads/categories/' . $filename;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if the category has subcategories
        if ($category->subcategories()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category because it has subcategories.');
        }

        // Delete image if exists
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
