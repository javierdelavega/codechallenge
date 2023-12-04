<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Order;

use Symfony\Component\Uid\Uuid;

// use Ramsey\Uuid\Uuid;

/**
 * Value Object for OrderLine id management.
 */
class OrderLineId
{
    private Uuid $id;

    /**
     * Constructor.
     *
     * @param Uuid|null an Uuid orderline identity
     */
    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?: Uuid::v4();
        // $this->id = $id ? :Uuid::uuid4()->toString();
    }

    /**
     * Static method for create an OrderLineId object.
     *
     * @param Uuid|null an Uuid orderline identity
     *
     * @return OrderLineId new OrderLineId object
     */
    public static function create(Uuid $anId = null): OrderLineId
    {
        return new static($anId);
    }

    /**
     * Get the id string.
     *
     * @return string the id string
     */
    public function __toString(): string
    {
        return $this->id->__toString();
    }

    /**
     * Get the id.
     *
     * @return string the id string
     */
    public function id(): string
    {
        return $this->id->__toString();
    }

    /**
     * Compare given OrderLineId with this OrderLineId.
     *
     * @param OrderLineId $orderLineId the orderline id to compare
     *
     * @return bool true if the id are equals, false if different
     */
    public function equals(OrderLineId $orderLineId): bool
    {
        return $this->id() === $orderLineId->id();
    }
}
