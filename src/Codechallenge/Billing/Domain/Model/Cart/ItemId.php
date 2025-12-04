<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use Symfony\Component\Uid\Uuid;

/**
 * Value Object for Item id management.
 */
class ItemId
{
    private Uuid $id;

    /**
     * Constructor.
     *
     * @param Uuid|null an Uuid item identity
     */
    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?: Uuid::v4();
    }

    /**
     * Static method for create an ItemId object.
     *
     * @param Uuid|null an Uuid item identity
     *
     * @return ItemId new ItemId object
     */
    public static function create(Uuid $anId = null): ItemId
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
     * Compare given ItemId with this ItemId.
     *
     * @param ItemId $itemId the item id to compare
     *
     * @return bool true if the id are equals, false if different
     */
    public function equals(ItemId $itemId): bool
    {
        return $this->id() === $itemId->id();
    }
}
