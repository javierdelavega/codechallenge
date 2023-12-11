<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * Factory for creating carts.
 */
interface CartFactory
{
    /**
     * Creates a cart owned by the given user.
     *
     * @param UserId $userId the user id
     */
    public function ofUser(UserId $userId): self;

    /**
     * Build the cart object.
     *
     * @param CartId the cart id
     *
     * @return Cart the cart object
     */
    public function build(CartId $cartId): Cart;
}
