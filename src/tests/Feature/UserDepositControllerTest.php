<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Enum\Coin;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserDepositControllerTest extends TestCase
{

    use DatabaseMigrations;
    /**
     * @dataProvider depositAmountData
     */
    public function test_it_can_deposit_money($coinAmount)
    {
        $user = User::factory()->withBuyerRole()->create();

        $previousBalance = $user->deposit;

        $this->actingAs($user)->postJson(route('user_deposit.store'), [
            'amount' => $coinAmount
        ])->assertSuccessful();

        $this->assertEquals($previousBalance + $coinAmount, $user->refresh()->deposit);
    }

    /**
     * @dataProvider depositAmountData
     */
    public function test_a_seller_can_not_deposit_money($coinAmount)
    {
        $user = User::factory()->withSellerRole()->create([
            'deposit' => 0
        ]);

        $previousBalance = $user->deposit;

        $this->actingAs($user)->postJson(route('user_deposit.store'), [
            'amount' => $coinAmount
        ])->assertForbidden();

        $this->assertEquals($previousBalance, $user->refresh()->deposit);
    }


    public function test_a_buyer_can_reset_user_deposit()
    {
        $user = User::factory()->withBuyerRole()->create();

        $this->actingAs($user)->postJson(route('user_deposit.reset'), [])->assertSuccessful();

        $this->assertEquals(0, $user->refresh()->deposit);
    }

    public function test_a_seller_can_not_reset_user_deposit()
    {
        $user = User::factory()->withSellerRole()->create();

        $this->actingAs($user)->postJson(route('user_deposit.reset'), [])->assertForbidden();
    }

    public function depositAmountData()
    {
        return array_map(fn(int $coinValue) => [$coinValue], Coin::values());
    }
}
