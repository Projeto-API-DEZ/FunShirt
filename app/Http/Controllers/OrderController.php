<?php

namespace App\Http\Controllers;

use App\Helpers\ReceiptHelper;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['customer.user', 'items']);
        $customer = trim((string) $request->string('customer'));
        $nif = trim((string) $request->string('nif'));
        $dateFrom = trim((string) $request->string('date_from'));
        $dateTo = trim((string) $request->string('date_to'));
        $status = trim((string) $request->string('status'));
        $orderId = trim((string) $request->string('order_id'));

        // Clientes so podem ver as proprias encomendas.
        if ($user->isCustomer()) {
            $query->where('customer_id', $user->id);
        } elseif ($user->isStaff()) {
            $query->where('status', 'pending');
        }

        if ($status !== '' && ! $user->isStaff()) {
            $query->where('status', $status);
        }

        if ($orderId !== '') {
            $query->where('id', $orderId);
        }

        if (($user->isAdmin() || $user->isStaff()) && $customer !== '') {
            $query->whereHas('customer.user', function ($builder) use ($customer) {
                $builder->where('name', 'like', "%{$customer}%");
            });
        }

        if (($user->isAdmin() || $user->isStaff()) && $nif !== '') {
            $query->where('nif', 'like', "%{$nif}%");
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        return view('orders.index', [
            'orders' => $orders,
            'filters' => [
                'customer' => $customer,
                'nif' => $nif,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'status' => $status,
                'order_id' => $orderId,
            ],
        ]);
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

    public function displayItemImage(Order $order, OrderItem $item): StreamedResponse
    {
        $this->authorizeOrderItemDisplayAccess($order, $item);
        [$disk, $path] = $this->resolveOrderItemImageLocation($item);

        abort_unless(Storage::disk($disk)->exists($path), 404, 'Image file not found.');

        return Storage::disk($disk)->response($path);
    }

    public function previewItemImage(Order $order, OrderItem $item)
    {
        $this->authorizeOrderItemImageStaffAccess($order, $item);
        [$disk, $path] = $this->resolveOrderItemImageLocation($item);

        abort_unless(Storage::disk($disk)->exists($path), 404, 'Image file not found.');

        return Storage::disk($disk)->response($path);
    }

    public function downloadItemImage(Order $order, OrderItem $item)
    {
        $this->authorizeOrderItemImageStaffAccess($order, $item);
        [$disk, $path] = $this->resolveOrderItemImageLocation($item);

        abort_unless(Storage::disk($disk)->exists($path), 404, 'Image file not found.');

        $name = $item->tshirtImage->name ?: ('order-item-' . $item->id);
        $extension = pathinfo((string) $item->tshirtImage->image_url, PATHINFO_EXTENSION);
        $downloadName = str($name)->slug('_') . ($extension ? '.' . $extension : '');

        return Storage::disk($disk)->download($path, $downloadName);
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

    public function cancel(Request $request, Order $order)
    {
        $user = Auth::user();

        if (! $user || ! $user->can('cancel', $order)) {
            abort(403, 'Unauthorized operation action.');
        }

        $validated = $request->validate([
            'reason_for_cancellation' => ['nullable', 'string', 'max:2000'],
        ]);

        $reason = trim((string) ($validated['reason_for_cancellation'] ?? ''));

        $order->update([
            'status' => 'canceled',
            'reason_for_cancellation' => $reason !== '' ? $reason : ($user->isAdmin() ? 'Canceled by administrator.' : 'Canceled by user.'),
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('alert-success', 'Order canceled successfully.');
    }

    protected function authorizeOrderItemDisplayAccess(Order $order, OrderItem $item): void
    {
        $user = Auth::user();

        if ($user->isCustomer() && $order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to order item image.');
        }

        if ($user->isStaff() && $order->status !== 'pending') {
            abort(403, 'Staff can only access pending order item images.');
        }

        abort_if(! $user->isAdmin() && ! $user->isStaff() && ! $user->isCustomer(), 403, 'Unauthorized access to order item image.');
        abort_if($item->order_id !== $order->id, 404, 'Order item does not belong to this order.');
        abort_if(! $item->tshirtImage?->image_url, 404, 'Image file not found.');
    }

    protected function authorizeOrderItemImageStaffAccess(Order $order, OrderItem $item): void
    {
        $user = Auth::user();

        abort_unless($user->isStaff(), 403, 'Only staff can access order item images.');
        abort_if($order->status !== 'pending', 403, 'Staff can only access pending order item images.');

        abort_if($item->order_id !== $order->id, 404, 'Order item does not belong to this order.');
        abort_if(! $item->tshirtImage?->image_url, 404, 'Image file not found.');
    }

    protected function resolveOrderItemImageLocation(OrderItem $item): array
    {
        if ($this->usesPrivateImageStorage($item->tshirtImage)) {
            return ['private', $this->migrateLegacyCustomImageToPrivate($item->tshirtImage)];
        }

        return ['public', 'tshirt_images/' . $item->tshirtImage->image_url];
    }

    protected function usesPrivateImageStorage(?TshirtImage $tshirtImage): bool
    {
        return (bool) ($tshirtImage?->customer_id || $tshirtImage?->custom);
    }

    protected function migrateLegacyCustomImageToPrivate(?TshirtImage $tshirtImage): string
    {
        $privatePath = 'tshirt_images_private/' . $tshirtImage->image_url;
        $publicPath = 'tshirt_images/' . $tshirtImage->image_url;

        if (! Storage::disk('private')->exists($privatePath) && Storage::disk('public')->exists($publicPath)) {
            Storage::disk('private')->put($privatePath, Storage::disk('public')->get($publicPath));
            Storage::disk('public')->delete($publicPath);
        }

        return $privatePath;
    }
}
