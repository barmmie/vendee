<?php   

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResetUserDepositAction
{
    public function execute(int $userId)
    {
        DB::beginTransaction();

        $user = User::lockForUpdate()->findOrFail($userId);

        $user->update([
            'deposit' => 0
        ]);

        DB::commit();

    }
}