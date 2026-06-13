<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomTshirtController extends Controller
{
    public function create(): View
    {
        $colors = Color::orderBy('name')->get();
        $priceConfig = Price::first();

        return view('customize.create', [
            'colors' => $colors,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
            'customPrice' => $priceConfig?->unit_price_own ?? 0,
            'customDiscountPrice' => $priceConfig?->unit_price_own_discount ?? ($priceConfig?->unit_price_own ?? 0),
            'discountThreshold' => $priceConfig?->qty_discount ?? null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if(auth()->user()?->isStaff(), 403, 'Staff accounts do not have shopping cart access.');

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'design_image' => ['required', 'image', 'max:4096'],
            'color_code' => ['required', 'exists:colors,code'],
            'size' => ['required', 'in:XS,S,M,L,XL'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $file = $request->file('design_image');
        $filename = 'custom_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('tshirt_images', $file, $filename);

        $design = TshirtImage::create([
            'customer_id' => auth()->id(),
            'category_id' => null,
            'name' => $validated['name'] ?: 'Custom T-Shirt',
            'description' => 'Uploaded custom design',
            'image_url' => $filename,
            'custom' => true,
        ]);

        $priceConfig = Price::first();
        $qty = (int) $validated['qty'];
        $unitPrice = $this->getCustomUnitPrice($priceConfig, $qty);

        $key = "custom_{$design->id}_{$validated['color_code']}_{$validated['size']}";
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
            $cart[$key]['sub_total'] = $cart[$key]['qty'] * $cart[$key]['unit_price'];
        } else {
            $cart[$key] = [
                'type' => 'custom',
                'is_custom' => true,
                'id' => $design->id,
                'tshirt_image_id' => $design->id,
                'name' => $design->name,
                'image_url' => $design->image_url,
                'color_code' => $validated['color_code'],
                'size' => $validated['size'],
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'sub_total' => $qty * $unitPrice,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Custom T-shirt added to cart.');
    }

    protected function getCustomUnitPrice(?Price $priceConfig, int $qty): float
    {
        if (! $priceConfig) {
            return 0;
        }

        if ($priceConfig->qty_discount && $qty >= $priceConfig->qty_discount) {
            return (float) $priceConfig->unit_price_own_discount;
        }

        return (float) $priceConfig->unit_price_own;
    }
}
