<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * Repository for manage carts.
 */
interface CartRepository
{
    /**
     * Adds a cart.
     */
    public function save(Cart $cart): void;

    /**
     * Removes a cart.
     */
    public function remove(Cart $cart): void;

    /**
     * Retrieves a cart of the given id.
     *
     * @param CartId the id of the cart
     *
     * @return Cart the Cart with requested id
     */
    public function cartOfId(CartId $cartId): ?Cart;

    /**
     * Retrieves a cart of the given user id.
     *
     * @param UserId the id of the user
     */
    public function cartOfUser(UserId $userId): ?Cart;

    /**
     * Gets a new unique Cart id.
     */
    public function nextIdentity(): CartId;
}
