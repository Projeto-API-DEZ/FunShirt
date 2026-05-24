<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;    
use Database\Factories\UserFactory;              
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;         
use Illuminate\Support\Str;                      
use Laravel\Fortify\TwoFactorAuthenticatable;    
use Illuminate\Database\Eloquent\Relations\HasOne; 
use Illuminate\Support\Facades\Storage; 

#[Fillable(['name', 'email', 'password', 'user_type', 'gender', 'photo_url', 'blocked'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
