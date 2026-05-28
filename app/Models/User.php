<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'email', 'password', 'user_type', 'gender', 'blocked', 'photo_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id');
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'C';
    }

    public function isStaff(): bool
    {
        return $this->user_type === 'F';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'A';
    }
}