<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Commission\PercentageCommissionPolicy;
use App\Domain\Converter;
use App\Domain\Currency;
use App\Domain\Exchange\FixedExchange;
use App\Domain\Exchanger;
use App\Domain\ExchangerException;
use App\Domain\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ExchangerTest extends TestCase
{
    #[DataProvider('saleMoneyDataProvider')]
    public function testSaleMoney(Money $expected, Money $moneyToSale, Currency $currency): void
    {
        $exchange = new FixedExchange();
        $exchange->register(Currency::EUR, Currency::GBP, 1.5678);
        $exchange->register(Currency::GBP, Currency::EUR, 1.5432);

        $exchanger = new Exchanger(new Converter($exchange), new PercentageCommissionPolicy(1));
        $result = $exchanger->sale($moneyToSale, $currency);

        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

    /**
     * @return mixed[]
     */
    public static function saleMoneyDataProvider(): array
    {
        return [
            'sale 100 EUR for GBP' => [Money::GBP(15521), Money::EUR(10000), Currency::GBP],
            'sale 100 GBP for EUR' => [Money::EUR(15278), Money::GBP(10000), Currency::EUR],
        ];
    }

    public function testSaleMoneyWithAmountLowerThanMinimalValue(): void
    {
        $this->expectException(ExchangerException::class);
        $this->expectExceptionMessage('Amount needs to be greater than minimal sale amount.');

        $exchanger = new Exchanger(new Converter(new FixedExchange()), new PercentageCommissionPolicy(1));
        $exchanger->sale(Money::EUR(0), Currency::EUR);
    }

    public function testSaleMoneyForSameCurrency(): void
    {
        $this->expectException(ExchangerException::class);
        $this->expectExceptionMessage('Cannot sale money for the same currency.');

        $exchanger = new Exchanger(new Converter(new FixedExchange()), new PercentageCommissionPolicy(1));
        $exchanger->sale(Money::EUR(100), Currency::EUR);
    }

    #[DataProvider('purchaseMoneyDataProvider')]
    public function testPurchaseSaleMoney(Money $expected, Money $moneyToPurchase, Currency $currency): void
    {
        $exchange = new FixedExchange();
        $exchange->register(Currency::EUR, Currency::GBP, 1.5678);
        $exchange->register(Currency::GBP, Currency::EUR, 1.5432);

        $exchanger = new Exchanger(new Converter($exchange), new PercentageCommissionPolicy(1));
        $result = $exchanger->purchase($moneyToPurchase, $currency);

        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

    /**
     * @return mixed[]
     */
    public static function purchaseMoneyDataProvider(): array
    {
        return [
            'purchase 100 GBP for EUR' => [Money::EUR(15586), Money::GBP(10000), Currency::EUR],
            'purchase 100 EUR for GBP' => [Money::GBP(15835), Money::EUR(10000), Currency::GBP],
        ];
    }

    public function testPurchaseMoneyWithAmountLowerThanMinimalValue(): void
    {
        $this->expectException(ExchangerException::class);
        $this->expectExceptionMessage('Amount needs to be greater than minimal purchase amount.');

        $exchanger = new Exchanger(new Converter(new FixedExchange()), new PercentageCommissionPolicy(1));
        $exchanger->purchase(Money::EUR(0), Currency::EUR);
    }

    public function testPurchaseMoneyForSameCurrency(): void
    {
        $this->expectException(ExchangerException::class);
        $this->expectExceptionMessage('Cannot purchase money for the same currency.');

        $exchanger = new Exchanger(new Converter(new FixedExchange()), new PercentageCommissionPolicy(1));
        $exchanger->purchase(Money::EUR(100), Currency::EUR);
    }
}
