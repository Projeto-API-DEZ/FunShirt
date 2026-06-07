<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['unit_price_catalog', 'unit_price_own', 'unit_price_catalog_discount', 'unit_price_own_discount', 'qty_discount'])]
class Price extends Model
{
    public $timestamps = false;
}
