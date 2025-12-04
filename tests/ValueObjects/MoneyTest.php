<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
  
  /** @test */
  public function canCreateWithValidAmount()
  {
    $isoCode = "EUR";
    $amount = 24.70;

    $money = new Money($amount, new Currency($isoCode));

    $this->assertInstanceOf(Money::class, $money);
  }

  /** @test */
  public function canNotCreateWithInvalidAmount()
  {
    $isoCode = "EUR";
    $amount = -24.70;

    $this->expectException(InvalidArgumentException::class);
    $money = new Money($amount, new Currency($isoCode));

    $this->assertNull($money);
  }

  /** @test */
  public function canGetTheAmount()
  {
    $isoCode = "EUR";
    $amount = 24.70;

    $money = new Money($amount, new Currency($isoCode));

    $this->assertEquals($amount, $money->amount());
  }

  /** @test */
  public function canGetTheCurrency()
  {
    $isoCode = "EUR";
    $amount = 24.70;
    $currency = new Currency($isoCode);

    $money = new Money($amount, $currency);

    $this->assertEquals($currency, $money->currency());
  }

  /** @test */
  public function canCompareMoney()
  {
    $money = new Money(10.20, new Currency("EUR"));
    $equivalentMoney = new Money(10.20, new Currency("EUR"));
    $differentMoney = new Money(10.20, new Currency("USD"));
    $anotherDifferentMoney = new Money(10.20, new Currency("USD"));

    $this->assertTrue($money->equals($equivalentMoney));
    $this->assertFalse($money->equals($differentMoney));
    $this->assertFalse($money->equals($anotherDifferentMoney));
  }

}