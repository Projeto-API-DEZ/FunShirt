<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $request->name];
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

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $request->name];
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
        if ($category->image_url) {
            Storage::disk('public')->delete('categories/' . $category->image_url);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}