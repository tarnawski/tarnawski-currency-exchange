<?php

declare(strict_types=1);

namespace App\Domain\Commission;

use App\Domain\Money;

class PercentageCommissionPolicy implements CommissionPolicyInterface
{
    private const int COMMISSION_PERCENTAGE_MINIMUM_VALUE = 0;

    public function __construct(private readonly int $percentage)
    {
        if ($percentage < self::COMMISSION_PERCENTAGE_MINIMUM_VALUE) {
            throw new CommissionPolicyException('Commission percentage value must be greater than or equal to 0.');
        }
    }

    public function calculate(Money $money): Money
    {
        return $money->multiply($this->percentage / 100);
    }
}
