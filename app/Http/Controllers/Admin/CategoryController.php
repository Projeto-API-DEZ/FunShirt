<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Requests\CategoryFormRequest;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryFormRequest $request)
    {
        $validated = $request->validated();
        $data = ['name' => $validated['name']];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image_url'] = basename($path);
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, Category $category)
    {
        $validated = $request->validated();
        $data = ['name' => $validated['name']];

        if ($request->hasFile('image')) {
            if ($category->image_url) {
                Storage::disk('public')->delete('categories/' . $category->image_url);
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image_url'] = basename($path);
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        TshirtImage::where('category_id', $category->id)->update([
            'category_id' => null,
        ]);

        if ($category->image_url) {
            Storage::disk('public')->delete('categories/' . $category->image_url);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
