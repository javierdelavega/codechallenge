<?php

namespace App\Tests\Entities;

use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Domain\Model\Cart\Item;
use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use App\Codechallenge\Billing\Domain\Model\Order\OrderLine;
use App\Codechallenge\Billing\Domain\Model\Order\OrderLineId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use PHPUnit\Framework\TestCase;


class OrderLineTest extends TestCase
{
  private $orderLineId;
  private $orderId;
  private $productId;
  private $quantity;
  private $price;


  protected function setUp() : void
  {
    $this->orderLineId = new OrderLineId();
    $this->orderId = new OrderId();
    $this->productId = new ProductId();
    $this->quantity = 1;
    $this->price = 54.75;
  }

  /** @test */
  public function returnsOrderLineId()
  {
    $orderLine = new OrderLine($this->orderLineId, $this->orderId, $this->productId, $this->quantity, $this->price);

    $this->assertEquals($this->orderLineId, $orderLine->id());
  }

  /** @test */
  public function returnsOrderId()
  {
    $orderLine = new OrderLine($this->orderLineId, $this->orderId, $this->productId, $this->quantity, $this->price);

    $this->assertEquals($this->orderId, $orderLine->orderId());
  }

  /** @test */
  public function returnsProductId()
  {
    $orderLine = new OrderLine($this->orderLineId, $this->orderId, $this->productId, $this->quantity, $this->price);

    $this->assertEquals($this->productId, $orderLine->productId());
  }

  /** @test */
  public function returnsQuantity()
  {
    $orderLine = new OrderLine($this->orderLineId, $this->orderId, $this->productId, $this->quantity, $this->price);

    $this->assertEquals($this->quantity, $orderLine->quantity());
  }

  /** @test */
  public function returnsPrice()
  {
    $orderLine = new OrderLine($this->orderLineId, $this->orderId, $this->productId, $this->quantity, $this->price);

    $this->assertEquals($this->price, $orderLine->price());
  }

}