<?php

namespace App\Tests\Entities;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Order\Order;
use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;


class OrderTest extends TestCase
{
  private $orderId;
  private $userId;
  private $address;
  private $productId;
  private $quantity;
  private $price;

  protected function setUp() : void
  {
    $this->orderId = new OrderId();
    $this->userId = new UserId();
    $this->address = "testAddress";
    $this->productId = new ProductId();
    $this->quantity = 2;
    $this->price = 52.40;
  }

  /** @test */
  public function returnsOrderId()
  {
    $order = new Order($this->orderId, $this->userId, $this->address);

    $this->assertEquals($this->address, $order->address());

  }

  /** @test */
  public function canAddOrderLines()
  {
    $added = false;
    $order = new Order($this->orderId, $this->userId, $this->address);
    $order->addLine($this->productId, $this->quantity, $this->price);

    $lines = $order->orderLines();

    foreach($lines as $line) {
      if ($line->productId()->equals($this->productId)) { 
        $added = true; 
      }
    }

    $this->assertTrue($added);
  }

  /** @test */
  public function canGetOrderProductCount()
  {
    $order = new Order($this->orderId, $this->userId, $this->address);
    $order->addLine($this->productId, $this->quantity, $this->price);

    $this->assertEquals($this->quantity, $order->productCount());  
  }

  /** @test */
  public function canGetOrderTotal()
  {
    $order = new Order($this->orderId, $this->userId, $this->address);
    $order->addLine($this->productId, $this->quantity, $this->price);
    $orderTotal = $this->quantity * $this->price;

    $this->assertEquals($orderTotal, $order->orderTotal());  
  }

  /** @test */
  public function returnsOrderDate()
  {
    $order = new Order($this->orderId, $this->userId, $this->address);

    $this->assertInstanceOf(DateTimeImmutable::class, $order->createdAt());

  }

}