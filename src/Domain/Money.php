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
    private function __construct(private int $amount, private Currency $currency)
    {
    }

    /**
     * Amount expressed in the smallest units (cents or pences)
     */
    public static function create(int $amount, Currency $currency): self
    {
        return new self($amount, $currency);
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
        try {
            return new self($arguments[0], Currency::from($method));
        } catch (ValueError) {
            throw new MoneyException('Cannot create money with specified currency.');
        }
    }

    public function add(Money $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new MoneyException('Cannot add money with different currencies.');
        }

        return new self($this->amount + $money->amount, $this->currency);
    }

    public function subtract(Money $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new MoneyException('Cannot subtract money with different currencies.');
        }

        return new self($this->amount - $money->amount, $this->currency);
    }

    public function multiply(float $multiplier): self
    {
        return new self(intval(round($this->amount * $multiplier)), $this->currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
