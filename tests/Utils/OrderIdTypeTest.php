<?php

namespace App\Tests\Utils;

use App\Codechallenge\Billing\Infrastructure\Persistence\Order\Doctrine\Types\OrderIdType;
use PHPUnit\Framework\TestCase;

class OrderIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $orderIdType = new OrderIdType();
    $this->assertEquals($orderIdType->getName(), OrderIdType::TYPE_NAME);
  }

}