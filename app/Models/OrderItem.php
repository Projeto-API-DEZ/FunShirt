<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'tshirt_image_id', 'color_code', 'size', 'quantity', 'unit_price', 'subtotal'])]
class OrderItem extends Model
{
    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function tshirtImage(): BelongsTo
    {
        return $this->belongsTo(TshirtImage::class)->withTrashed();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_code', 'code');
    }
}