<?php

namespace App\Domain\Commission;

use App\Domain\Money;

interface CommissionPolicyInterface
{
    /**
     * Calculate commission for exchange base on money.
     */
    public function calculate(Money $money): Money;
}
