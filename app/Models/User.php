<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'user_type', 'gender', 'blocked', 'photo_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    public function initials(): string
    {
        $words = Str::of($this->name)
            ->trim()
            ->explode(' ')
            ->filter(fn (string $word) => $word !== '')
            ->values();

        if ($words->isEmpty()) {
            return 'U';
        }

        $initials = Str::substr($words->first(), 0, 1);

        if ($words->count() > 1) {
            $initials .= Str::substr($words->last(), 0, 1);
        }

        return Str::upper($initials);
    }

    public function getPhotoFullUrlAttribute()
    {
        $photoPath = $this->normalizedPhotoPath();

        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            return route('user.photo', ['path' => $photoPath], false);
        }

        return asset('storage/photos/anonymous.png');
    }

    public function hasUploadedPhoto(): bool
    {
        $photoPath = $this->normalizedPhotoPath();

        return (bool) ($photoPath && Storage::disk('public')->exists($photoPath));
    }

    public function normalizedPhotoPath(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        return Str::startsWith($this->photo_url, 'photos/')
            ? $this->photo_url
            : "photos/{$this->photo_url}";
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
