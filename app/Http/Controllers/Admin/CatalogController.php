<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TshirtImage;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();

        $query = TshirtImage::whereNull('customer_id')->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $images = $query->latest()->paginate(12)->withQueryString();

        // Get current catalog price
        $priceConfig = \App\Models\Price::first();
        $catalogPrice = $priceConfig ? $priceConfig->unit_price_catalog : 0;

        return view('catalog.index', compact('images', 'categories', 'colors', 'catalogPrice'));
    }

    public function show(TshirtImage $tshirtImage)
    {
        if ($tshirtImage->customer_id !== null) {
            abort(403, 'Unauthorized access to custom customer design.');
        }

        $colors = Color::all();
        return view('catalog.show', compact('tshirtImage', 'colors'));
    }
}
