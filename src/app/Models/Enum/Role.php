<?php

namespace App\Models\Enum;

enum Role: string {
    case SELLER = 'seller';
    case BUYER = 'buyer';

    public static function values(): array {
        return array_map(fn($role) => $role->value, static::cases());
    }
}