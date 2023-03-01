<?php
namespace App\Actions;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Actions\Result\BuyProductResult;
use App\Exceptions\InsufficientUserBalanceException;
use App\Exceptions\InsufficientProductStockException;

class BuyProductAction
{

    public function __construct(private ComputeChangeAction $computeChangeAction)
    {
    }
    
    public function execute(int $userId, int $productId, int $desiredQuantity)
    {
        DB::beginTransaction();

        try {
            $user = User::lockForUpdate()->findOrFail($userId);
            $product = Product::lockForUpdate()->findOrFail($productId);

            if($product->amountAvailable < $desiredQuantity) {
                throw InsufficientProductStockException::create($product->productName, $desiredQuantity);
            }
            
            $depositToDebit = $desiredQuantity * $product->cost;

            if($user->deposit < $depositToDebit) {
                throw InsufficientUserBalanceException::create($user->username, $product->productName, $desiredQuantity);
            }

            $user->decrement('deposit', $depositToDebit);
            $product->decrement('amountAvailable', $desiredQuantity);

            Order::create([
                'productId'=> $productId,
                'userId' => $userId,
                'quantity' => $desiredQuantity,
                'purchaseCost' => $product->cost,
                'amountSpent' => $depositToDebit
            ]);

            DB::commit();

            return new BuyProductResult(
                product: $product,
                amountSpent:  $depositToDebit,
                change: $this->computeChangeAction->execute($user->refresh()->deposit)
            );
            
        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}