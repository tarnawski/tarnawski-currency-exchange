<?php

declare(strict_types=1);

namespace App\Tests\Domain\Commision;

use App\Domain\Commission\CommissionPolicyException;
use App\Domain\Commission\PercentageCommissionPolicy;
use App\Domain\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PercentageCommissionPolicyTest extends TestCase
{
    #[DataProvider('percentageCommissionPolicyDataProvider')]
    public function testCalculatePercentageCommissionPolicy(Money $expected, Money $money, int $percentage): void
    {
        $result = (new PercentageCommissionPolicy($percentage))->calculate($money);

        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

    /**
     * @return mixed[]
     */
    public static function percentageCommissionPolicyDataProvider(): array
    {
        return [
            'no commission' => [Money::EUR(0), Money::EUR(100), 0],
            '1 percent commission' => [Money::EUR(1), Money::EUR(100), 1],
            '80 percent commission' => [Money::EUR(80), Money::EUR(100), 80],
            '125 percent commission' => [Money::EUR(125), Money::EUR(100), 125],
        ];
    }

    public function testSetUpPercentageCommissionPolicyWithIncorrectPercentageValue(): void
    {
        $this->expectException(CommissionPolicyException::class);
        $this->expectExceptionMessage('Commission percentage value must be greater than or equal to 0.');

        new PercentageCommissionPolicy(-2);
    }
}
