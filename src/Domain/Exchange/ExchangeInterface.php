<?php

namespace App\Domain\Exchange;

use App\Domain\Currency;

interface ExchangeInterface
{
    /**
     * Returns exchange rate for the currencies.
     */
    public function get(Currency $from, Currency $to): float;
}
