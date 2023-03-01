<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Enum\Coin;
use Illuminate\Support\Facades\DB;

class DepositCoinAction
{
    public function execute(int $userId, Coin $coin): void
    {

        DB::beginTransaction();

        try {
            $user = User::lockForUpdate()->findOrFail($userId);

            $user->increment('deposit', $coin->value);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();

        }
        
    }
}