<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Converter;
use App\Domain\ConverterException;
use App\Domain\Currency;
use App\Domain\Exchange\FixedExchange;
use App\Domain\Money;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $exchange = new FixedExchange();
        $exchange->register(Currency::EUR, Currency::GBP, 1.5678);

        $converter = new Converter($exchange);
        $result = $converter->convert(Money::EUR(100), Currency::GBP);

        $this->assertEquals(157, $result->getAmount());
        $this->assertEquals(Currency::GBP, $result->getCurrency());
    }

    public function testConvertWithSameCurrency(): void
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionMessage('Cannot convert money to the same currency.');

        $converter = new Converter(new FixedExchange());
        $converter->convert(Money::EUR(100), Currency::EUR);
    }
}
