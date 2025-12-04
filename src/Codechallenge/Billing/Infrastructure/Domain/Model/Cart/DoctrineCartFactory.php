<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartFactory;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;

/**
 * Factory for creating carts using Doctrine ORM.
 */
class DoctrineCartFactory implements CartFactory
{
    private UserId $userId;

    /**
     * Creates a cart owned by the given user.
     *
     * @param UserId $userId the user id
     */
    public function ofUser(UserId $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Build the cart object.
     *
     * @param CartId the cart id
     *
     * @return Cart the cart object
     */
    public function build(CartId $cartId): Cart
    {
        return new Cart($cartId, $this->userId);
    }
}
