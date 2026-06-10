<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'image_url', 'custom'])]
class Category extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}