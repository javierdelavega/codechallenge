<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Domain\Model;

/**
 * Value object for represent a money concept. (currency and amount).
 */
class Money
{
    private float $amount;
    private Currency $currency;

    /**
     * Constructor.
     *
     * @param float    $anAmount  the amount
     * @param Currency $aCurrency the currency value object
     */
    public function __construct(float $anAmount, Currency $aCurrency)
    {
        $this->setAmount($anAmount);
        $this->setCurrency($aCurrency);
    }

    /**
     * Sets the amount.
     *
     * @param float $anAmount the amount must be positive decimal or int number
     */
    private function setAmount(float $anAmount): void
    {
        if ($anAmount < 0) {
            throw new \InvalidArgumentException();
        }
        $this->amount = (float) $anAmount;
    }

    /**
     * Sets the currency iso code.
     *
     * @param Currency $aCurrency the currency value object
     */
    private function setCurrency(Currency $aCurrency): void
    {
        $this->currency = $aCurrency;
    }

    /**
     * Gets the amount.
     *
     * @return float the amount
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * Gets the currency.
     *
     * @return Currency the currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * Compares two money objects. The currency and amount must be the same to evaluate as equals.
     *
     * @param Money $money the money to compare
     *
     * @return bool true if currency and amount are equals, false if different
     */
    public function equals(Money $money): bool
    {
        return $money->currency()->equals($this->currency()) && $money->amount() === $this->amount();
    }
}
