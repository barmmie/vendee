<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Enum\Coin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function test_it_can_purchase_a_product()
    {

        $initialDeposit = 100;
        $user = User::factory()->create([
            'deposit' =>  $initialDeposit
        ]);

        $productQuantityAvailable = 10;
        $productQuantityToBuy = 5;
        $productCost = 10;

        $product = Product::factory()->create([
            'amountAvailable' => $productQuantityAvailable,
            'cost' => $productCost
        ]);

        $this->actingAs($user)
            ->postJson(route('purchase.store'), [
                'productId' => $product->id,
                'quantity' => $productQuantityToBuy
            ])->assertSuccessful();

        $this->assertEquals($initialDeposit - ($productCost * $productQuantityToBuy), $user->refresh()->deposit);
        $this->assertEquals($productQuantityAvailable - $productQuantityToBuy, $product->refresh()->amountAvailable);
        $this->assertDatabaseHas('orders', [
            'productId'=> $product->id,
            'userId' => $user->id,
            'purchaseCost' => $productCost,
            'quantity' => $productQuantityToBuy
        ]);
    }

    public function test_it_can_not_purchase_a_product_with_availability_less_than_desired_quantity()
    {

        $initialDeposit = 100;
        $user = User::factory()->create([
            'deposit' =>  $initialDeposit
        ]);

        $productQuantityAvailable = 2;
        $productQuantityToBuy = 5;
        $productCost = 10;

        $product = Product::factory()->create([
            'amountAvailable' => $productQuantityAvailable,
            'cost' => $productCost
        ]);

        $this->actingAs($user)
            ->postJson(route('purchase.store'), [
                'productId' => $product->id,
                'quantity' => $productQuantityToBuy
            ])->assertBadRequest()
            ;

        $this->assertEquals($initialDeposit, $user->refresh()->deposit);
        $this->assertEquals($productQuantityAvailable, $product->refresh()->amountAvailable);
    }

    public function test_it_can_not_purchase_a_product_when_user_deposit_is_less_than_total_product_cost()
    {

        $initialDeposit = 10;
        $user = User::factory()->create([
            'deposit' =>  $initialDeposit
        ]);

        $productQuantityAvailable = 5;
        $productQuantityToBuy = 2;
        $productCost = 10;

        $product = Product::factory()->create([
            'amountAvailable' => $productQuantityAvailable,
            'cost' => $productCost
        ]);

        $this->actingAs($user)
            ->postJson(route('purchase.store'), [
                'productId' => $product->id,
                'quantity' => $productQuantityToBuy
            ])->assertBadRequest()
            ;

        $this->assertEquals($initialDeposit, $user->refresh()->deposit);
        $this->assertEquals($productQuantityAvailable, $product->refresh()->amountAvailable);
    }

}
