<?php

namespace App\Models;

use App\Helpers\ReceiptHelper;
use App\Mail\OrderConfirmed;
use App\Mail\OrderStatusUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

#[Fillable(['status', 'customer_id', 'date', 'notes', 'nif', 'address', 'payment_type', 'payment_ref', 'receipt_url', 'total_price', 'reason_for_cancellation'])]
class Order extends Model
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        static::created(function ($order) {
            Mail::to($order->customer->user->email)->send(new OrderConfirmed($order));
        });

        static::updated(function ($order) {
            if ($order->wasChanged('status') && $order->status === 'closed') {
                if (!$order->receipt_url) {
                    ReceiptHelper::generate($order);
                }
                Mail::to($order->customer->user->email)->send(new OrderStatusUpdate($order));
            }
        });
    }
}