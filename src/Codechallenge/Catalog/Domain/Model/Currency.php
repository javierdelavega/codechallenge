<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Domain\Model;

/**
 * Value object for represent a currency.
 */
class Currency
{
    private string $isoCode;

    /**
     * Constructor.
     *
     * @param string $anIsoCode the iso code of the currency
     */
    public function __construct(string $anIsoCode)
    {
        $this->setIsoCode($anIsoCode);
    }

    /**
     * Sets the iso code of the currency. Currently only validates that have 3 capital A-Z characters.
     *
     * @param string $anIsoCode the iso code
     *
     * @throws \InvalidArgumentException if the iso code is not 3 capital A-Z characters
     */
    private function setIsoCode(string $anIsoCode): void
    {
        if (!preg_match('/^[A-Z]{3}$/', $anIsoCode)) {
            throw new \InvalidArgumentException();
        }
        $this->isoCode = $anIsoCode;
    }

    /**
     * Get the iso code of the currency.
     *
     * @return string the iso code of the currency
     */
    public function isoCode(): string
    {
        return $this->isoCode;
    }

    /**
     * Compares two currency objects. Evaluate as equals if the isoCode is the same.
     *
     * @param Currency $currency the currency to compare
     *
     * @return bool true if the iso codes are the same
     */
    public function equals(Currency $currency): bool
    {
        return $currency->isoCode() === $this->isoCode();
    }
}
