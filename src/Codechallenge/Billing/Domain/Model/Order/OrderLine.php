<?php

namespace App\Codechallenge\Billing\Domain\Model\Order;

use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use App\Codechallenge\Billing\Domain\Model\Order\OrderLineId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

class OrderLine
{

  private OrderLineId $orderLineId;
  private OrderId $orderId;
  private ProductId $productId;
  private $quantity;
  private $price;

  public function __construct(OrderLineId $orderLineId, OrderId $orderId, ProductId $productId, $quantity, $price)
  {
    $this->orderLineId = $orderLineId;
    $this->orderId = $orderId;
    $this->productId = $productId;
    $this->quantity = $quantity;
    $this->price = $price;
  }

  public function id()
  {
    return $this->orderLineId;
  }

  public function orderId()
  {
    return $this->orderId;
  }

  public function quantity()
  {
    return $this->quantity;
  }

  public function price()
  {
    return $this->price;
  }

  public function productId()
  {
    return $this->productId;
  }

}