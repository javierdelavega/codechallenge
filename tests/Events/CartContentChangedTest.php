<?php

namespace App\Tests\Events;

use App\Codechallenge\Billing\Domain\Model\Cart\CartContentChanged;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;


class CartContentChangedTest extends TestCase
{

  /** @test */
  public function canGetCartId()
  {
    $cartId = new CartId();
    $event = new CartContentChanged($cartId);

    $this->assertEquals($cartId, $event->cartId());
  }

  /** @test */
  public function canGetOccurredOn()
  {
    $cartId = new CartId();
    $event = new CartContentChanged($cartId);

    $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredOn());
  }


}