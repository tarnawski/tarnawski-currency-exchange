<?php

declare(strict_types=1);

namespace App\Domain\Exchange;

use App\Domain\Currency;

class FixedExchange implements ExchangeInterface
{
    /**
     * @var array<array<float>>
     */
    private array $rates = [];

    public function register(Currency $from, Currency $to, float $rate): void
    {
        if ($from === $to) {
            throw new ExchangeException('Cannot register exchange rate for the same currency.');
        }

        $this->rates[$from->name][$to->name] = $rate;
    }

    public function get(Currency $from, Currency $to): float
    {
        if (!isset($this->rates[$from->name][$to->name])) {
            throw new ExchangeException('Cannot found exchange rate for specified currencies.');
        }

        return $this->rates[$from->name][$to->name];
    }
}
