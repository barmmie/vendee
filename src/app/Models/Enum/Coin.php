<?php

namespace App\Models\Enum;

enum Coin: int {
    case FIVE = 5;
    case TEN = 10;
    case TWENTY = 20;
    case FIFTY = 50;
    case HUNDRED = 100;

    public static function values()
    {
        return array_map(fn($role) => $role->value, static::cases());
    }
}
