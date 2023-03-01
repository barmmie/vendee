<?php

namespace App\Actions\Result;

use App\Models\Product;

class BuyProductResult
{

    /**
     * 
     */
    public function __construct(
        public readonly int $amountSpent, 
        public readonly Product $product, 
        public readonly array $change)
    {
        # code...
    }
}