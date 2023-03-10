<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sellerId', 'productName', 'cost', 'amountAvailable'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'sellerId');
    }
}
