<?php

declare(strict_types=1);

namespace App\Domain;

use ValueError;

/**
 * @method static Money EUR(int $amount)
 * @method static Money GBP(int $amount)
 */
final readonly class Money
{
    public const int SCALE = 4;

    private string $amount;
    private Currency $currency;

    private function __construct(string $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Amount expressed in the smallest units (cents or pences)
     */
    public static function create(int $amount, Currency $currency): self
    {
        return new self((string) $amount, $currency);
    }

    /**
     * Factory method for Money object. Example usage: Money::EUR(500)
     *
     * @param string $method
     * @param mixed[] $arguments
     * @return Money
     * @throws MoneyException
     */
    public static function __callStatic(string $method, array $arguments): self
    {
        if (!isset($arguments[0])) {
            throw new MoneyException('Amount should be provided.');
        }
        if (!is_int($arguments[0])) {
            throw new MoneyException('Amount should be expressed as integer value.');
        }

        try {
            return new self((string) $arguments[0], Currency::from($method));
        } catch (ValueError) {
            throw new MoneyException('Cannot create money with specified currency.');
        }
    }

    public function add(Money $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new MoneyException('Cannot add money with different currencies.');
        }

        return new self(bcadd($this->amount, $money->amount, self::SCALE), $this->currency);
    }

    public function subtract(Money $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new MoneyException('Cannot subtract money with different currencies.');
        }

        return new self(bcsub($this->amount, $money->amount, self::SCALE), $this->currency);
    }

    public function multiply(float $multiplier): self
    {
        $multiplier =  number_format($multiplier, self::SCALE, '.', '');
        $amount = bcmul($this->amount, $multiplier, self::SCALE);
        $roundedAmount = bcadd($amount, '0.5', 0); //TODO Make sure that this is bast way for rounding.

        return new self($roundedAmount, $this->currency);
    }

    public function getAmount(): int
    {
        return (int) $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
