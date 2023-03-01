<?php

namespace App\Actions;

use App\Models\Enum\Coin;

class ComputeChangeAction
{
    public function execute(int $amount)
    {
        $coins = Coin::values();
        rsort($coins);

        $balanceBreakdown = [];

        foreach ($coins as $coin) {
            $balanceBreakdown[$coin] = floor($amount / $coin);

            $amount = $amount % $coin;
        }

        return $balanceBreakdown;
    }
}
