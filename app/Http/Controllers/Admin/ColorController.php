<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('name')->paginate(15);
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:colors,code',
            'name' => 'required|string|max:255',
        ]);

        Color::create($request->only(['code', 'name']));
        return redirect()->route('admin.colors.index')->with('success', 'Color created.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:colors,code,' . $color->code . ',code',
            'name' => 'required|string|max:255',
        ]);

        $color->update($request->only(['code', 'name']));
        return redirect()->route('admin.colors.index')->with('success', 'Color updated.');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted.');
    }
}