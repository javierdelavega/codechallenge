<?php

namespace App\Tests\Utils;

use App\Codechallenge\Billing\Infrastructure\Persistence\Order\Doctrine\Types\OrderLineIdType;
use PHPUnit\Framework\TestCase;

class OrderLineIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $orderLineIdType = new OrderLineIdType();
    $this->assertEquals($orderLineIdType->getName(), OrderLineIdType::TYPE_NAME);
  }

}