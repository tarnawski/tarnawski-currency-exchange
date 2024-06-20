<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Commission\CommissionPolicyInterface;

final readonly class Exchanger
{
    private const int MINIMUM_SALE_AMOUNT = 1;
    private const int MINIMUM_PURCHASE_AMOUNT = 1;

    public function __construct(
        private Converter $converter,
        private CommissionPolicyInterface $commissionPolicy,
    ) {
    }

    public function sale(Money $money, Currency $currency): Money
    {
        if ($money->getAmount() < self::MINIMUM_SALE_AMOUNT) {
            throw new ExchangerException('Amount needs to be greater than minimal sale amount.');
        }
        if ($money->getCurrency() === $currency) {
            throw new ExchangerException('Cannot sale money for the same currency.');
        }

        $result = $this->converter->convert($money, $currency);
        $commission = $this->commissionPolicy->calculate($result);

        return $result->subtract($commission);
    }

    public function purchase(Money $money, Currency $currency): Money
    {
        if ($money->getAmount() < self::MINIMUM_PURCHASE_AMOUNT) {
            throw new ExchangerException('Amount needs to be greater than minimal purchase amount.');
        }
        if ($money->getCurrency() === $currency) {
            throw new ExchangerException('Cannot purchase money for the same currency.');
        }

        $result = $this->converter->convert($money, $currency);
        $commission = $this->commissionPolicy->calculate($result);

        return $result->add($commission);
    }
}
