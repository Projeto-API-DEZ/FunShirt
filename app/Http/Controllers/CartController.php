<?php

namespace App\Http\Controllers;

use App\Models\TshirtImage;
use App\Models\Price;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function ensureCartAccess(): void
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Staff accounts do not have shopping cart access.');
        }
    }

    /**
     * Display cart contents.
     */
    public function index()
    {
        $this->ensureCartAccess();

        $cart = session()->get('cart', []);
        $priceConfig = Price::first();
        $colors = \App\Models\Color::orderBy('name')->get();
        $discountThreshold = $priceConfig?->qty_discount;
        $originalTotal = 0;
        $total = 0;

        foreach ($cart as $key => $item) {
            $baseUnitPrice = $this->getBaseUnitPrice($priceConfig, $item['type']);
            $discountUnitPrice = $this->getDiscountUnitPrice($priceConfig, $item['type']);
            $qualifiesDiscount = $discountThreshold && $item['qty'] >= $discountThreshold;
            $originalSubTotal = $item['qty'] * $baseUnitPrice;
            $discountAmount = max($originalSubTotal - $item['sub_total'], 0);
            $discountRate = $baseUnitPrice > 0
                ? round((($baseUnitPrice - $discountUnitPrice) / $baseUnitPrice) * 100, 2)
                : 0;

            $cart[$key]['original_unit_price'] = $baseUnitPrice;
            $cart[$key]['discount_unit_price'] = $discountUnitPrice;
            $cart[$key]['original_sub_total'] = $originalSubTotal;
            $cart[$key]['discount_amount'] = $discountAmount;
            $cart[$key]['discount_rate'] = $discountRate;
            $cart[$key]['qualifies_discount'] = (bool) $qualifiesDiscount;
            $cart[$key]['discount_threshold'] = $discountThreshold;

            $originalTotal += $originalSubTotal;
            $total += $item['sub_total'];
        }

        $totalSavings = max($originalTotal - $total, 0);
        $user = auth()->user();
        $checkoutEnabled = $user?->isCustomer() && ! $user->blocked;
        $checkoutLabel = 'Proceed to Checkout';
        $checkoutHref = route('checkout.index');

        if (! $user) {
            $checkoutLabel = 'Login to Checkout';
            $checkoutHref = route('login');
        } elseif (! $user->isCustomer()) {
            $checkoutLabel = 'Customers Only';
            $checkoutHref = null;
        }

        return view('cart.index', compact(
            'cart',
            'colors',
            'total',
            'originalTotal',
            'totalSavings',
            'checkoutEnabled',
            'checkoutLabel',
            'checkoutHref'
        ));
    }

    public function show()
    {
        return $this->index();
    }

    /**
     * Add an item to the cart (catalog image only).
     */
    public function add(Request $request, ?TshirtImage $tshirtImage = null)
    {
        $this->ensureCartAccess();

        $tshirtImage ??= TshirtImage::findOrFail($request->input('tshirt_image_id'));

        // Only catalog images allowed here
        if ($tshirtImage->customer_id !== null) {
            abort(403, 'Custom images must be added via their own route.');
        }

        $request->validate([
            'color_code' => 'required|exists:colors,code',
            'size'       => 'required|in:XS,S,M,L,XL',
            'qty'        => 'required|integer|min:1',
        ]);

        $priceConfig = Price::first();
        $qty = (int) $request->qty;

        // Determine unit price (with discount if quantity >= threshold)
        $unitPrice = $this->getUnitPrice($priceConfig, 'catalog', $qty);

        // Unique key for cart item: type_id_color_size
        $key = "catalog_{$tshirtImage->id}_{$request->color_code}_{$request->size}";

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            // Update existing item
            $cart[$key]['qty'] += $qty;
            $cart[$key]['sub_total'] = $cart[$key]['qty'] * $cart[$key]['unit_price'];
        } else {
            // Add new item
            $cart[$key] = [
                'type'            => 'catalog',
                'is_custom'       => false,
                'id'              => $tshirtImage->id,
                'tshirt_image_id' => $tshirtImage->id,
                'name'            => $tshirtImage->name,
                'image_url'       => $tshirtImage->image_url,
                'color_code'      => $request->color_code,
                'size'            => $request->size,
                'qty'             => $qty,
                'unit_price'      => $unitPrice,
                'sub_total'       => $qty * $unitPrice,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Item added to cart.');
    }

    /**
     * Update quantity, color, or size of an existing cart item.
     */
    public function update(Request $request, $key)
    {
        $this->ensureCartAccess();

        $cart = session()->get('cart', []);
        if (!isset($cart[$key])) {
            return redirect()->route('cart.show')->with('error', 'Item not found.');
        }

        $item = &$cart[$key];

        $request->validate([
            'qty'        => 'sometimes|integer|min:0',
            'color_code' => 'sometimes|exists:colors,code',
            'size'       => 'sometimes|in:XS,S,M,L,XL',
        ]);

        // Update quantity (if zero, remove item)
        if ($request->has('qty')) {
            $newQty = (int) $request->qty;
            if ($newQty <= 0) {
                unset($cart[$key]);
                session()->put('cart', $cart);
                return redirect()->route('cart.show')->with('success', 'Item removed.');
            }
            $item['qty'] = $newQty;
            // Recalculate unit price (discount might change)
            $priceConfig = Price::first();
            $item['unit_price'] = $this->getUnitPrice($priceConfig, $item['type'], $newQty);
            $item['sub_total'] = $newQty * $item['unit_price'];
        }

        // Update color
        if ($request->has('color_code')) {
            $item['color_code'] = $request->color_code;
        }

        // Update size
        if ($request->has('size')) {
            $item['size'] = $request->size;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Cart updated.');
    }

    /**
     * Remove a single item from the cart.
     */
    public function remove($key)
    {
        $this->ensureCartAccess();

        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.show')->with('success', 'Item removed.');
    }

    /**
     * Clear entire cart.
     */
    public function clear()
    {
        $this->ensureCartAccess();

        session()->forget('cart');
        return redirect()->route('cart.show')->with('success', 'Cart cleared.');
    }

    /**
     * Proceed to checkout (redirect to login if guest).
     * Will be fully implemented in G4.
     */
    public function checkout()
    {
        $this->ensureCartAccess();

        if (!auth()->check()) {
            // Save intended URL to redirect back after login
            session()->put('url.intended', route('cart.checkout'));
            return redirect()->route('login')->with('error', 'Please login to complete your purchase.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        return redirect()->route('checkout.index');
    }

    /**
     * Helper to get unit price based on type and quantity (with discount).
     */
    private function getUnitPrice($priceConfig, $type, $quantity)
    {
        if (!$priceConfig) {
            return 0;
        }
        $threshold = $priceConfig->qty_discount;
        if ($type === 'catalog') {
            return ($quantity >= $threshold) ? $priceConfig->unit_price_catalog_discount : $priceConfig->unit_price_catalog;
        } else {
            return ($quantity >= $threshold) ? $priceConfig->unit_price_own_discount : $priceConfig->unit_price_own;
        }
    }

    private function getBaseUnitPrice($priceConfig, $type)
    {
        if (! $priceConfig) {
            return 0;
        }

        return $type === 'catalog'
            ? $priceConfig->unit_price_catalog
            : $priceConfig->unit_price_own;
    }

    private function getDiscountUnitPrice($priceConfig, $type)
    {
        if (! $priceConfig) {
            return 0;
        }

        return $type === 'catalog'
            ? $priceConfig->unit_price_catalog_discount
            : $priceConfig->unit_price_own_discount;
    }
}
