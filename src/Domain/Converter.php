<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exchange\ExchangeInterface;

final readonly class Converter
{
    public function __construct(private ExchangeInterface $exchange)
    {
    }

    public function convert(Money $money, Currency $currency): Money
    {
        if ($money->getCurrency() === $currency) {
            throw new ConverterException('Cannot convert money to the same currency.');
        }

        $rate = $this->exchange->get($money->getCurrency(), $currency);
        $amount = $money->multiply($rate)->getAmount();

        return Money::create($amount, $currency);
    }
}
