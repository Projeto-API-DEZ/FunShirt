<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public function download(Order $order)
    {
        $user = auth()->user();
        if (!$user) abort(403);
        $isAdmin = $user->user_type === 'A';
        $isOwner = $order->customer_id === ($user->customer->id ?? null);
        if (!$isAdmin && !$isOwner) abort(403);

        if (!$order->receipt_url || !Storage::disk('private')->exists('pdf_receipts/' . $order->receipt_url)) {
            abort(404);
        }

        return Storage::disk('private')->download('pdf_receipts/' . $order->receipt_url, 'receipt.pdf');
    }
}