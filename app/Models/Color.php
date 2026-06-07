<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table(key: 'code', keyType: 'string', incrementing: false, timestamps: false)]
#[Fillable(['code', 'name', 'custom'])]
class Color extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
}