<?php

declare(strict_types=1);

namespace App\Tests\Domain\Exchange;

use App\Domain\Currency;
use App\Domain\Exchange\ExchangeException;
use App\Domain\Exchange\FixedExchange;
use PHPUnit\Framework\TestCase;

class FixedExchangeTest extends TestCase
{
    public function testRegisterAndGetExchangeRate(): void
    {
        $exchange = new FixedExchange();
        $exchange->register(Currency::EUR, Currency::GBP, 1.5678);

        $result = $exchange->get(Currency::EUR, Currency::GBP);

        $this->assertEquals(1.5678, $result);
    }

    public function testRegisterExchangeRateForSameCurrency(): void
    {
        $this->expectException(ExchangeException::class);
        $this->expectExceptionMessage('Cannot register exchange rate for the same currency.');

        (new FixedExchange())->register(Currency::EUR, Currency::EUR, 1.5678);
    }

    public function testGetExchangeRateWhenRateIsNotRegistered(): void
    {
        $this->expectException(ExchangeException::class);
        $this->expectExceptionMessage('Cannot found exchange rate for specified currencies.');

        (new FixedExchange())->get(Currency::EUR, Currency::EUR);
    }
}
