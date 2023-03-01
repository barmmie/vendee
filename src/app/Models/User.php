<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Enum\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'deposit'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    protected $appends = [
        'is_seller', 'is_buyer'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'sellerId');
    }

    public function getIsSellerAttribute() {
        return $this->role === Role::SELLER->value;
    }

    public function getIsBuyerAttribute() {
        return $this->role === Role::BUYER->value;
    }

}
