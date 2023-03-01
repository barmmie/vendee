<?php
namespace App\Http\Controllers\Api;

use App\Models\Enum\Coin;
use App\Actions\DepositCoinAction;
use App\Http\Resources\UserResource;
use App\Actions\ResetUserDepositAction;
use App\Http\Requests\DepositCoinRequest;
use App\Http\Requests\ResetDepositRequest;

class UserDepositController
{
    public function deposit(DepositCoinRequest $request, DepositCoinAction $depositCoinAction)
    {
        $user = $request->user();

        $depositCoinAction->execute(
            $user->id, 
            Coin::from((int)$request->get('amount'))
        );

        return new UserResource($user->refresh());
    }

    public function reset(ResetDepositRequest $request, ResetUserDepositAction $resetDepositAction)
    {
        $user = $request->user();

        $resetDepositAction->execute($user->id);

        return new UserResource($user->refresh());
        
    }
}