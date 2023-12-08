<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Order;

use App\Codechallenge\Catalog\Domain\Model\ProductId;

class OrderLine
{
    public function __construct(
        private OrderLineId $orderLineId,
        private OrderId $orderId,
        private ProductId $productId,
        private int $quantity,
        private float $price
    ) {
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
