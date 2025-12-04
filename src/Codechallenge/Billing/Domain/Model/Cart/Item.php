<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Cart Item class
 * The item contains information of the product in the cart: product id and quantity.
 */
class Item
{
    /**
     * Constructor.
     *
     * @param ItemId    $itemId    the item id
     * @param CartId    $cartId    the cartId wich owns the item
     * @param ProductId $productId the product id
     * @param int       $quantity  the quantity
     */
    public function __construct(
        private ItemId $itemId,
        private CartId $cartId,
        private ProductId $productId,
        private int $quantity
    ) {
    }

    /**
     * Gets the item id.
     *
     * @return ItemId the item id
     */
    public function id(): ItemId
    {
        return $this->itemId;
    }

    /**
     * Gets the cart id.
     *
     * @return CartId the cart id
     */
    public function cartId(): CartId
    {
        return $this->cartId;
    }

    /**
     * Gets the quantity.
     *
     * @return int the quantity
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * Gets the product id.
     *
     * @return ProductId the product id
     */
    public function productId(): ProductId
    {
        return $this->productId;
    }

    /**
     * Sets the quantity of the item in the cart.
     *
     * @param int $quantity the quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
