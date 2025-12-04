<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Shared\Domain\Event\DomainEvent;

/**
 * Event published when the cart content changed. for example a new product added.
 */
class CartContentChanged implements DomainEvent
{
    private CartId $cartId;
    private \DateTimeImmutable $occurredOn;

    /**
     * Constructor.
     *
     * @param CartId $cartId the id of the cart
     */
    public function __construct(CartId $cartId)
    {
        $this->cartId = $cartId;
        $this->occurredOn = new \DateTimeImmutable();
    }

    /**
     * Gets the id of the cart.
     *
     * @return CartId the id of the cart
     */
    public function cartId(): CartId
    {
        return $this->cartId;
    }

    /**
     * Gets the time when the event occured.
     *
     * @return \DateTimeImmutable the time when the event occured
     */
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
