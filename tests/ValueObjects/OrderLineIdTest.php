<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Billing\Domain\Model\Order\OrderLineId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class OrderLineIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticOrderLineId()
  {
    $orderLineId = OrderLineId::create();
    $this->assertInstanceOf(OrderLineId::class, $orderLineId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $orderLineId = new OrderLineId($id);

    $this->assertEquals($orderLineId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $orderLineId = new OrderLineId($id);

    $this->assertEquals($orderLineId->id(), $id);
  }

  /** @test */
  public function canCompareWithOtherId()
  {
    $id = Uuid::v4();
    $anotherId = Uuid::v4();

    $orderLineId = new OrderLineId($id);
    $equivalentOrderLineId = new OrderLineId($id);
    $differentOrderLineId = new OrderLineId($anotherId);

    $this->assertTrue($orderLineId->equals($equivalentOrderLineId));
    $this->assertFalse($orderLineId->equals($differentOrderLineId));
  }

}