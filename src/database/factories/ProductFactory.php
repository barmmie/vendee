<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'productName' => $this->faker->company(),
            'cost' => $this->faker->randomNumber(1) * 5,
            'amountAvailable' => $this->faker->randomNumber(1),
            'sellerId' => User::factory()->withSellerRole()
        ];
    }
}
