<?php

namespace App\Tests\Utils;

use App\Codechallenge\Billing\Infrastructure\Persistence\Cart\Doctrine\Types\CartIdType;
use PHPUnit\Framework\TestCase;

class CartIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $cartIdType = new CartIdType();
    $this->assertEquals($cartIdType->getName(), CartIdType::TYPE_NAME);
  }

}