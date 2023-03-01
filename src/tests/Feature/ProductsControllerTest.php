<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsControllerTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function test_can_create_a_product(): void
    {

        $user = User::factory()->withSellerRole()->create();


        $productName = $this->faker->company();
        $cost = $this->faker->randomNumber(1) * 5;
        $amountAvailable = $this->faker->randomNumber(2);

        $productData = [
            'productName' => $productName,
            'cost' => $cost,
            'amountAvailable' => $amountAvailable
        ];

        $this->actingAs($user)
            ->postJson(route('product.store'), $productData)
            ->assertSuccessful();

        $this->assertDatabaseHas('products', array_merge($productData, ['sellerId' => $user->id]));

    }

    public function test_can_a_buyer_cannot_create_a_product(): void
    {

        $user = User::factory()->withBuyerRole()->create();


        $productName = $this->faker->company();
        $cost = $this->faker->randomNumber(1) * 5;
        $amountAvailable = $this->faker->randomNumber(2);

        $productData = [
            'productName' => $productName,
            'cost' => $cost,
            'amountAvailable' => $amountAvailable
        ];

        $this->actingAs($user)
            ->postJson(route('product.store'), $productData)
            ->assertForbidden();


    }

    public function test_can_update_a_product(): void
    {

        $product = Product::factory()->create();

        $user = $product->seller;

        $productName = $this->faker->company();
        $cost = $this->faker->randomNumber(2) * 5;
        $amountAvailable = $this->faker->randomNumber(2);

        $productData = [
            'productName' => $productName,
            'cost' => $cost,
            'amountAvailable' => $amountAvailable
        ];

        $this->actingAs($user)
            ->putJson(route('product.update', $product->id), $productData)
            ->assertSuccessful();

        $this->assertDatabaseHas('products', array_merge($productData, ['sellerId' => $user->id]));

    }

    public function test_can_not_update_a_product_created_by_another_user(): void
    {

        $product = Product::factory()->create();
        $user = User::factory()->withSellerRole()->create();;

        $productName = $this->faker->company();
        $cost = $this->faker->randomNumber(2) * 5;
        $amountAvailable = $this->faker->randomNumber(2);

        $productData = [
            'productName' => $productName,
            'cost' => $cost,
            'amountAvailable' => $amountAvailable
        ];

        $this->actingAs($user)
            ->putJson(route('product.update', $product->id), $productData)
            ->assertForbidden();


    }


    public function test_can_delete_a_product(): void
    {

        $product = Product::factory()->create();
        $user = $product->seller;

        $this->actingAs($user)
            ->deleteJson(route('product.destroy', $product->id))
            ->assertSuccessful();

        $this->assertDatabaseEmpty('products');

    }

    public function test_can_not_delete_a_product_created_by_another_user(): void
    {

        $product = Product::factory()->create();
        $user = User::factory()->withSellerRole()->create();

        $this->actingAs($user)
            ->deleteJson(route('product.update', $product->id))
            ->assertForbidden();

    }
}
