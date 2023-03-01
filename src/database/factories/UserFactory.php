<?php

namespace Database\Factories;

use App\Models\Enum\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $username = fake()->userName();
        return [
            'username' => $username,
            'deposit' => fake()->numberBetween(1,10),
            'role' => fake()->randomElement(Role::values()),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function withSellerRole(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => Role::SELLER->value,
            ];
        });
    }

    public function withBuyerRole(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => Role::BUYER->value,
            ];
        });
    }

    public function withPassword(string $password): Factory
    {
        return $this->state(function (array $attributes) use ($password) {
            return [
                'password' => Hash::make($password),
            ];
        });
    }

    public function withAlreadyAuthenticatedToken(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'api_token' => hash('sha256', "api_token.{$attributes['username']}"), //
            ];
        });
    }
}
