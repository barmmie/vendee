<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Actions\BuyProductAction;
use App\Exceptions\BuyProductException;
use App\Http\Requests\BuyProductRequest;

class PurchaseController
{
    public function store(BuyProductRequest $request, BuyProductAction $buyProductAction)
    {
        try {
            $result = $buyProductAction->execute(
                $request->user()->id,
                $request->get('productId'),
                $request->get('quantity')
            );
            
            return response()->json([
                'amountSpent' => $result->amountSpent,
                'product' => $result->product,
                'change' => $result->change
            ]);
        } catch (BuyProductException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}