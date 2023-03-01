<?php
namespace App\Exceptions;

use Exception;

class InsufficientUserBalanceException extends Exception  implements BuyProductException
{
    public static function create(string $username, string $productName, int $desiredQuantity)
    {
        return new static("User `{$username}` does not does not have deposit to purchase for `{$desiredQuantity}` items of `{$productName}`.");
    }
}