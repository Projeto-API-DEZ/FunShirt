<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\TshirtImage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;



#[Fillable(['id', 'nif', 'address', 'default_payment_type', 'default_payment_ref', 'custom'])]
#[Table(timestamps: false)]

class Customer extends Model
{    
    use SoftDeletes;
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
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

