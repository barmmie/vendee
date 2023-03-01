<?php
namespace App\Exceptions;

use Exception;

class InsufficientProductStockException extends Exception  implements BuyProductException
{
    public static function create(string $productName, int $desiredQuantity)
    {
        return new static("Product named `{$productName}` does not have enough inventory for `{$desiredQuantity}` items.");
    }
}