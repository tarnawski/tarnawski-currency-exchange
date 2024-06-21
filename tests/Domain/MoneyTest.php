<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Currency;
use App\Domain\Money;
use App\Domain\MoneyException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testFactoryMethod(): void
    {
        $result = Money::EUR(125);

        $this->assertEquals(125, $result->getAmount());
        $this->assertEquals(Currency::EUR, $result->getCurrency());
    }

    public function testFactoryMethodWhenNoValueProvided(): void
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount should be provided.');

        Money::EUR();
    }

    public function testFactoryMethodWhenNoIntegerValueProvided(): void
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount should be expressed as integer value.');

        Money::EUR('unknown');
    }

    public function testFactoryMethodWhenNoUnknownCurrencyProvided(): void
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Cannot create money with specified currency.');

        Money::UNKNOWN(100);
    }

    public function testMoneyAdd(): void
    {
        $result = (Money::EUR(100))->add(Money::EUR(125));

        $this->assertEquals(225, $result->getAmount());
        $this->assertEquals(Currency::EUR, $result->getCurrency());
    }

    public function testMoneySubtract(): void
    {
        $result = (Money::EUR(125))->subtract(Money::EUR(100));

        $this->assertEquals(25, $result->getAmount());
        $this->assertEquals(Currency::EUR, $result->getCurrency());
    }

    #[DataProvider('moneyMultiplyDataProvider')]
    public function testMoneyMultiply(Money $expected, Money $money, float $multiplier): void
    {
        $result = $money->multiply($multiplier);

        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

    /**
     * @return mixed[]
     */
    public static function moneyMultiplyDataProvider(): array
    {
        return [
            'simple multiply' => [Money::EUR(200), Money::EUR(100), 2.0],
            'multiply with rounding' => [Money::EUR(154), Money::EUR(100), 1.5432],
            'multiply with rounding - round half up' => [Money::EUR(135), Money::EUR(100), 1.3456],
        ];
    }
}
