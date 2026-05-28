<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'nif', 'address', 'default_payment_type', 'default_payment_ref'])]
class Customer extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id')->withTrashed();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}