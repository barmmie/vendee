<?php

namespace Tests\Unit;

use App\Actions\ComputeChangeAction;
use App\Models\Enum\Coin;
use PHPUnit\Framework\TestCase;

class ComputeChangeActionTest extends TestCase
{
    /**
     * @dataProvider coinChangeResults
     */
    public function test_that_true_is_true($amount, $result): void
    {
        $change = (new ComputeChangeAction())->execute($amount);

        foreach (Coin::values() as $coinValue) {
            $this->assertEquals($result[$coinValue] ?? 0, $change[$coinValue]);
        }
    }

    public function coinChangeResults(): array
    {
        return [
            [5, [5 => 1]],
            [10, [10 => 1]],
            [30, [20 => 1, 10 => 1]],
            [40, [20 => 2]],
            [35, [20 => 1, 10 => 1, 5 => 1]],
            [100, [100 => 1]]
        ];
    }
}
