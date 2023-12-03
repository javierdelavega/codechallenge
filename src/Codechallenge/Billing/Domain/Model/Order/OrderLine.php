<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Order;

use App\Codechallenge\Catalog\Domain\Model\ProductId;

class OrderLine
{
    private OrderLineId $orderLineId;
    private OrderId $orderId;
    private ProductId $productId;
    private int $quantity;
    private float $price;

    public function __construct(OrderLineId $orderLineId, OrderId $orderId, ProductId $productId, int $quantity, float $price)
    {
        $this->orderLineId = $orderLineId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function id(): OrderLineId
    {
        return $this->orderLineId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
