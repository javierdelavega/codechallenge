<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Order Model represents an order of an user.
 */
class Order
{
    private OrderId $orderId;
    private UserId $userId;
    private Collection $orderLines;
    private string $address;
    private int $productCount;
    private float $orderTotal;
    private \DateTimeImmutable $createdAt;

    /**
     * Constructor.
     *
     * @param OrderId $orderId the order id
     * @param UserId  $userId  the user id
     * @param string  $address the post address to send the order
     */
    public function __construct(OrderId $orderId, UserId $userId, string $address)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->orderLines = new ArrayCollection();
        $this->address = $address;
        $this->productCount = 0;
        $this->orderTotal = 0;
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Gets the order id.
     *
     * @return OrderId the order id
     */
    public function id(): OrderId
    {
        return $this->orderId;
    }

    /**
     * Get the order lines.
     *
     * @return Collection the lines of the order
     */
    public function orderLines(): Collection
    {
        $this->orderLines->toArray();

        return $this->orderLines;
    }

    /**
     * Get the userId who owns the order.
     *
     * @return UserId the user id
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * Add line to the order.
     *
     * @param ProductId $productId the product id
     * @param int       $quantity  the quantity
     * @param float     $price     the price
     */
    public function addLine(ProductId $productId, int $quantity, float $price): void
    {
        $orderLine = new OrderLine(
            new OrderLineId(),
            $this->id(),
            $productId,
            $quantity,
            $price
        );

        $this->orderLines->add($orderLine);
        $this->productCount += $orderLine->quantity();
        $this->orderTotal += $orderLine->quantity() * $orderLine->price();
    }

    /**
     * Get the address to send the order.
     *
     * @return string the address
     */
    public function address(): string
    {
        return $this->address;
    }

    /**
     * Get the order total price.
     *
     * @return float the total price
     */
    public function orderTotal(): float
    {
        return $this->orderTotal;
    }

    /**
     * Get the product count of the order.
     *
     * @return int the product count
     */
    public function productCount(): int
    {
        return $this->productCount;
    }

    /**
     * Get the creation date of the order.
     *
     * @return \DateTimeImmutable the creation date
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
