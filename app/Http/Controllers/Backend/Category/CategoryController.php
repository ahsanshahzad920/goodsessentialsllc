<?php

namespace App\Http\Controllers\Backend\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all(); // Load subcategories (children)

        return view('backend.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Load only parent categories
        return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'required',
        ]);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        Category::create($data);

        return redirect()->route('categories.index')
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
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);

        // Bug fix: this used to only set the slug when it was empty, so the
        // URL never followed a renamed category. Only regenerate when the
        // name actually changed, excluding this category's own row from the
        // uniqueness check so re-saving the same name doesn't get suffixed
        // against itself, and 301 the old URL to the new one.
        $oldSlug = $category->slug;
        $nameChanged = $request->input('name') !== $category->name;

        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->slug = $nameChanged
            ? $this->uniqueSlug($category->name, $category->id)
            : ($oldSlug ?: $this->uniqueSlug($category->name, $category->id));
        $category->updated_by = Auth::id();
        $category->save();

        if ($category->slug !== $oldSlug && $oldSlug) {
            $now = now();

            DB::table('redirects')->updateOrInsert(
                ['old_path' => 'shop/' . $oldSlug],
                [
                    'old_path'    => 'shop/' . $oldSlug,
                    'new_path'    => 'shop/' . $category->slug,
                    'status_code' => 301,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
