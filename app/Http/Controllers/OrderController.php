<?php

namespace App\Http\Controllers;

use App\Helpers\ReceiptHelper;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['customer.user', 'items']);

        // Clientes so podem ver as proprias encomendas.
        if ($user->isCustomer()) {
            $query->where('customer_id', $user->id);
        } elseif ($user->isStaff()) {
            $query->where('status', 'pending');
        }

        if ($request->filled('status') && ! $user->isStaff()) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_id')) {
            $query->where('id', $request->order_id);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        if ($user->isCustomer() && $order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to order details.');
        }

        if ($user->isStaff() && $order->status !== 'pending') {
            abort(403, 'Staff can only access pending orders.');
        }

        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);
        return view('orders.show', compact('order'));
    }

    public function previewItemImage(Order $order, OrderItem $item)
    {
        $this->authorizeOrderItemImageAccess($order, $item);

        $path = 'tshirt_images/' . $item->tshirtImage->image_url;

        abort_unless(Storage::disk('public')->exists($path), 404, 'Image file not found.');

        return Storage::disk('public')->response($path);
    }

    public function downloadItemImage(Order $order, OrderItem $item)
    {
        $this->authorizeOrderItemImageAccess($order, $item);

        $path = 'tshirt_images/' . $item->tshirtImage->image_url;

        abort_unless(Storage::disk('public')->exists($path), 404, 'Image file not found.');

        $name = $item->tshirtImage->name ?: ('order-item-' . $item->id);
        $extension = pathinfo((string) $item->tshirtImage->image_url, PATHINFO_EXTENSION);
        $downloadName = str($name)->slug('_') . ($extension ? '.' . $extension : '');

        return Storage::disk('public')->download($path, $downloadName);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && ! $user->isStaff()) {
            abort(403, 'Unauthorized operation action.');
        }

        if ($user->isStaff()) {
            abort_if($order->status !== 'pending', 403, 'Staff can only close pending orders.');

            $request->validate([
                'status' => 'required|in:closed',
            ]);
        } else {
            $request->validate([
                'status' => 'required|in:pending,closed,canceled',
                'reason_for_cancellation' => 'required_if:status,canceled|string|nullable',
            ]);
        }

        $order->status = $request->status;

        if ($request->status === 'canceled') {
            $order->reason_for_cancellation = $request->reason_for_cancellation;
        } else {
            $order->reason_for_cancellation = null;
        }

        $order->save();

        if ($request->status === 'closed' && ! $order->fresh()?->receipt_url) {
            ReceiptHelper::generate($order);
            $order->refresh();
        }

        if ($user->isStaff()) {
            return redirect()->route('orders.index')->with('alert-success', 'Pending order closed successfully.');
        }

        return back()->with('alert-success', "Order tracking status updated to {$request->status}.");
    }

    public function cancel(Order $order)
    {
        $user = Auth::user();

        if (! $user || ! $user->can('cancel', $order)) {
            abort(403, 'Unauthorized operation action.');
        }

        $order->update([
            'status' => 'canceled',
            'reason_for_cancellation' => $order->reason_for_cancellation ?: 'Canceled by user.',
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('alert-success', 'Order canceled successfully.');
    }

    protected function authorizeOrderItemImageAccess(Order $order, OrderItem $item): void
    {
        $user = Auth::user();

        abort_unless($user->isStaff(), 403, 'Only staff can access order item images.');
        abort_if($order->status !== 'pending', 403, 'Staff can only access pending order item images.');

        abort_if($item->order_id !== $order->id, 404, 'Order item does not belong to this order.');
        abort_if(! $item->tshirtImage?->image_url, 404, 'Image file not found.');
    }
}
