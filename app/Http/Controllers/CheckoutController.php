<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use App\Requests\CheckoutFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('alert-danger', 'Your shopping cart is empty.');
        }

        $customer = Auth::user()->customer;

        return view('checkout.index', compact('cart', 'customer'));
    }

    public function store(CheckoutFormRequest $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('alert-danger', 'Your shopping cart is empty.');
        }

        $validated = $request->validated();
        $user = Auth::user();

        // O total e recalculado no servidor para impedir manipulacao no cliente.
        $pricing = Price::first();
        $totalPrice = 0;

        $orderItemsData = [];
        foreach ($cart as $item) {
            $type = !empty($item['is_custom']) ? 'custom' : 'catalog';
            $unitPrice = $this->resolveUnitPrice($pricing, $type, (int) $item['qty']);

            $subTotal = $unitPrice * $item['qty'];
            $totalPrice += $subTotal;

            $orderItemsData[] = [
                'tshirt_image_id' => $item['tshirt_image_id'],
                'color_code' => $item['color_code'],
                'size' => $item['size'],
                'qty' => $item['qty'],
                'unit_price' => $unitPrice,
                'sub_total' => $subTotal,
            ];
        }

        DB::transaction(function () use ($user, $validated, $totalPrice, $orderItemsData) {
            $order = Order::create([
                'customer_id' => $user->id,
                'status' => 'pending',
                'date' => now()->toDateString(),
                'total_price' => $totalPrice,
                'notes' => $validated['notes'] ?? null,
                'nif' => $validated['nif'],
                'address' => $validated['address'],
                'payment_type' => $validated['payment_type'],
                'payment_ref' => $validated['payment_ref'],
            ]);

            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }
        });

        session()->forget('cart');

        return redirect()->route('orders.index')->with('alert-success', 'Order submitted successfully! Tracking number generated.');
    }

    protected function resolveUnitPrice(?Price $pricing, string $type, int $qty): float
    {
        if (! $pricing) {
            return 0;
        }

        $useDiscount = $pricing->qty_discount && $qty >= $pricing->qty_discount;

        if ($type === 'custom') {
            return (float) ($useDiscount ? $pricing->unit_price_own_discount : $pricing->unit_price_own);
        }

        return (float) ($useDiscount ? $pricing->unit_price_catalog_discount : $pricing->unit_price_catalog);
    }
}
