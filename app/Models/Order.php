<?php

namespace App\Models;

use App\Helpers\ReceiptHelper;
use App\Mail\OrderConfirmed;
use App\Mail\OrderClosedMail;
use App\Mail\OrderPendingMail;
use App\Mail\OrderStatusUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use Throwable;

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
            static::sendMailSafely($order, new OrderConfirmed($order));
            static::sendMailSafely($order, new OrderPendingMail($order));
        });

        static::updated(function ($order) {
            if (! $order->wasChanged('status')) {
                return;
            }

            if ($order->status === 'closed') {
                if (! $order->receipt_url) {
                    ReceiptHelper::generate($order);
                }

                static::sendMailSafely($order, new OrderClosedMail($order));
                static::sendMailSafely($order, new OrderStatusUpdate($order));
            }

            if ($order->status === 'canceled') {
                static::sendMailSafely($order, new OrderStatusUpdate($order));
            }
        });
    }

    protected static function sendMailSafely(Order $order, object $mailable): void
    {
        try {
            Mail::to($order->customer->user->email)->send($mailable);
        } catch (Throwable $exception) {
            report($exception);
            logger()->warning('Order email send failed.', [
                'order_id' => $order->id,
                'mailable' => $mailable::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
