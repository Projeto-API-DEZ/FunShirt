<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Requests\ColorFormRequest;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::query()
            ->withCount(['orderItems as order_items_usage_count'])
            ->orderBy('name')
            ->paginate(15);
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(ColorFormRequest $request)
    {
        Color::create($request->validated());
        return redirect()->route('admin.colors.index')->with('success', 'Color created.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(ColorFormRequest $request, Color $color)
    {
        $validated = $request->validated();

        if ($validated['code'] === $color->code) {
            $color->name = $validated['name'];
            $color->save();

            return redirect()->route('admin.colors.index')->with('success', 'Color updated.');
        }

        DB::transaction(function () use ($color, $validated) {
            Color::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
            ]);

            DB::table('order_items')
                ->where('color_code', $color->code)
                ->update(['color_code' => $validated['code']]);

            $color->delete();
        });

        return redirect()->route('admin.colors.index')->with('success', 'Color updated.');
    }

    public function destroy(Color $color)
    {
        $hasOrderUsage = DB::table('order_items')
            ->where('color_code', $color->code)
            ->exists();

        $hasSessionCartUsage = collect(session('cart', []))
            ->contains(fn ($item) => ($item['color_code'] ?? null) === $color->code);

        if ($hasOrderUsage || $hasSessionCartUsage) {
            return redirect()
                ->route('admin.colors.index')
                ->withErrors([
                    'color_delete' => 'This color cannot be deleted because it is already used by existing orders or by the current shopping cart session.',
                ]);
        }

        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted.');
    }
}
