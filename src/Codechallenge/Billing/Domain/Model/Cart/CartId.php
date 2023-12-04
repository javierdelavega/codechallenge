<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use Symfony\Component\Uid\Uuid;

/**
 * Value Object for Cart id management.
 */
class CartId
{
    private Uuid $id;

    /**
     * Constructor.
     *
     * @param Uuid|null an Uuid cart identity
     */
    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?: Uuid::v4();
    }

    /**
     * Static method for create an CartId object.
     *
     * @param Uuid|null an Uuid cart identity
     *
     * @return CartId new CartId object
     */
    public static function create(Uuid $anId = null): CartId
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
     * Compare given CartId with this CartId.
     *
     * @param CartId $cartId the cart id to compare
     *
     * @return bool true if the id are equals, false if different
     */
    public function equals(CartId $cartId): bool
    {
        return $this->id() === $cartId->id();
    }
}
