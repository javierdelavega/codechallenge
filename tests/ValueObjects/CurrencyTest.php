<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Catalog\Domain\Model\Currency;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
  
  /** @test */
  public function canReturnIsoValue()
  {
    $isoCode = "EUR";

    $currency = new Currency($isoCode);

    $this->assertEquals($isoCode, $currency->isoCode());
  }

  /** @test */
  public function canNotCreateWithInvalidIsoCode()
  {
    $this->expectException(InvalidArgumentException::class);
    $currency = new Currency("EURO");

    $this->expectException(InvalidArgumentException::class);
    $currency = new Currency("eur");

    $this->expectException(InvalidArgumentException::class);
    $currency = new Currency("EU3");

    $this->assertNull($currency);
  }

  /** @test */
  public function canCompareCurrency()
  {
    $currency = new Currency("EUR");
    $equivalentCurrency = new Currency("EUR");
    $differentCurrency = new Currency("USD");

    $this->assertTrue($currency->equals($equivalentCurrency));
    $this->assertFalse($currency->equals($differentCurrency));
  }

}