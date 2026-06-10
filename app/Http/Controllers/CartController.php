<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Requests\CartItemFormRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $pricing = Price::first();
        $totalQuantity = array_sum(array_column($cart, 'qty'));
        $useDiscount = $pricing && $totalQuantity >= $pricing->qty_discount;

        $totalPrice = 0;
        foreach ($cart as $key => $item) {
            if ($item['is_custom']) {
                $unitPrice = $useDiscount ? $pricing->unit_price_custom_discount : $pricing->unit_price_custom;
            } else {
                $unitPrice = $useDiscount ? $pricing->unit_price_catalog_discount : $pricing->unit_price_catalog;
            }
            $cart[$key]['unit_price'] = $unitPrice;
            $cart[$key]['sub_total'] = $unitPrice * $item['qty'];
            $totalPrice += $cart[$key]['sub_total'];
        }

        session()->put('cart', $cart);

        return view('cart.index', compact('cart', 'totalPrice', 'totalQuantity'));
    }

    public function store(CartItemFormRequest $request)
    {
        $validated = $request->validated();
        $tshirt = TshirtImage::findOrFail($validated['tshirt_image_id']);
        $color = Color::findOrFail($validated['color_code']);

        $cartKey = $validated['tshirt_image_id'] . '-' . $validated['color_code'] . '-' . $validated['size'];
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $validated['qty'];
        } else {
            $cart[$cartKey] = [
                'tshirt_image_id' => $tshirt->id,
                'name' => $tshirt->name,
                'image_url' => $tshirt->image_url,
                'is_custom' => !is_null($tshirt->customer_id),
                'color_code' => $color->code,
                'color_name' => $color->name,
                'size' => $validated['size'],
                'qty' => $validated['qty'],
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('alert-success', 'Item added to your shopping cart.');
    }

    public function update(Request $request, $key)
    {
        $request->validate(['qty' => 'required|integer|min:1|max:100']);
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] = $request->qty;
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('alert-success', 'Cart quantities updated.');
        }

        return redirect()->route('cart.index')->with('alert-danger', 'Item not found inside the current session.');
    }

    public function destroy($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('alert-success', 'Item discarded from cart.');
        }

        return redirect()->route('cart.index')->with('alert-danger', 'Item not found inside the current session.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('alert-success', 'Shopping cart cleared.');
    }
}
