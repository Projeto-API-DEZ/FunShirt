<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['catalog_price', 'custom_price', 'qty_discount_threshold', 'catalog_discount_price', 'custom_discount_price'])]
class Price extends Model
{
    public $timestamps = false;
}