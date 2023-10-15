<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class OrderIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticOrderId()
  {
    $orderId = OrderId::create();
    $this->assertInstanceOf(OrderId::class, $orderId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $orderId = new OrderId($id);

    $this->assertEquals($orderId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $orderId = new OrderId($id);

    $this->assertEquals($orderId->id(), $id);
  }

  /** @test */
  public function canCompareWithOtherId()
  {
    $id = Uuid::v4();
    $anotherId = Uuid::v4();

    $orderId = new OrderId($id);
    $equivalentOrderId = new OrderId($id);
    $differentOrderId = new OrderId($anotherId);

    $this->assertTrue($orderId->equals($equivalentOrderId));
    $this->assertFalse($orderId->equals($differentOrderId));
  }

}