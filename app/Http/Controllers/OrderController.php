<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        }

        if ($request->filled('status')) {
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

        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Unauthorized operation action.');
        }

        $request->validate([
            'status' => 'required|in:pending,closed,canceled',
            'reason_for_cancellation' => 'required_if:status,canceled|string|nullable',
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;

        if ($request->status === 'canceled') {
            $order->reason_for_cancellation = $request->reason_for_cancellation;
        }

        if ($request->status === 'closed' && $oldStatus !== 'closed') {
            // Generate a dummy receipt PDF if no real PDF library is found
            // In a real scenario, we would use DomPDF or similar here.
            $receiptName = 'receipt_' . $order->id . '_' . Str::random(10) . '.pdf';
            $content = "FUNSHIRT RECEIPT\nOrder #" . $order->id . "\nCustomer: " . $order->customer->user->name . "\nTotal: " . $order->total_price;
            Storage::disk('local')->put("receipts/" . $receiptName, $content);
            $order->receipt_url = $receiptName;
        }

        $order->save();

        // Notify customer about status change
        Mail::to($order->customer->user->email)->send(new OrderStatusUpdated($order));

        return back()->with('alert-success', "Order tracking status updated to {$request->status}.");
    }

    public function downloadReceipt(Order $order)
    {
        $user = Auth::user();
        if ($user->isCustomer() && $order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to invoice receipt.');
        }

        if (!$order->receipt_url || !Storage::disk('local')->exists("receipts/{$order->receipt_url}")) {
            abort(404, 'Receipt invoice file not found.');
        }

        return Storage::disk('local')->download("receipts/{$order->receipt_url}", "Receipt-Order-{$order->id}.pdf");
    }
}
