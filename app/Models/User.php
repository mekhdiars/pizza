<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'address',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return false;
    }

    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class)->with('product');
    }

    public function getCartTotal(): float
    {
        $total = $this->cartProducts->sum(function (CartProduct $cartProduct) {
            return $cartProduct->product->price * $cartProduct->quantity;
        });

        return round($total, 2);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->with('products');
    }
}
