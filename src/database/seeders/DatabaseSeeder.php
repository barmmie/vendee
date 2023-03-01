<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

         $seller = \App\Models\User::factory()->withPassword('password')->withSellerRole()->create([
             'username' => 'seller',
         ]);

        $buyer = \App\Models\User::factory()->withPassword('password')->withBuyerRole()->create([
            'username' => 'buyer',
            'deposit' => 100
        ]);
        Product::factory(5)->create();

        $product = Product::factory(5)->create([
            'sellerId' => $seller->id
        ]);
    }
}
