<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Shared\Domain\Aggregate\AggregateRoot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Cart Model
 * The users have one cart
 * Users can add products to the cart
 * Registered users can confirm the cart to place an order.
 */
class Cart extends AggregateRoot
{
    private CartId $cartId;
    private UserId $userId;
    private Collection $items;
    private int $productCount;
    private float $cartTotal;

    /**
     * Constructor.
     *
     * @param CartId $cartId the cart id
     * @param UserId $userId the user id
     */
    public function __construct(CartId $cartId, UserId $userId)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
        $this->items = new ArrayCollection();
        $this->productCount = 0;
        $this->cartTotal = 0;
    }

    /**
     * Gets the cart id.
     *
     * @return CartId the cart id
     */
    public function id(): CartId
    {
        return $this->cartId;
    }

    /**
     * Get the cart items.
     *
     * @return Collection|Item[] the items in the cart
     */
    public function items(): Collection
    {
        $this->items->toArray();

        return $this->items;
    }

    /**
     * Get the userId who owns the cart.
     *
     * @return UserId the user id
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * Add product to the cart.
     *
     * @param ProductId $productId the product id
     * @param int       $quantity  the quantity
     */
    public function addProduct(ProductId $productId, int $quantity): void
    {
        $alReadyInCart = false;
        $prevQuantity = 0;

        // if the product is already in cart, update it
        foreach ($this->items as $item) {
            if ($item->productId()->equals($productId)) {
                $prevQuantity = $item->quantity();
                $alReadyInCart = true;
            }
        }

        if ($alReadyInCart) {
            $this->updateProduct($productId, $quantity + $prevQuantity);
        } else {
            // if not in the cart, add it
            $item = new Item(
                new ItemId(),
                $this->id(),
                $productId,
                $quantity
            );

            $this->items->add($item);
            $this->productCount += $item->quantity();
            $this->record(new CartContentChanged($this->cartId));
        }
    }

    /**
     * Remove product from the cart.
     *
     * @param ProductId $productId the product id to remove
     *
     * @throws ProductNotInCartException if the product is not in the cart
     */
    public function removeProduct(ProductId $productId): void
    {
        $key = null;
        $i = 0;
        $this->items->toArray();
        foreach ($this->items as $item) {
            if ($item->productId()->equals($productId)) {
                $key = $i;
                $prevQuantity = $item->quantity();
            }
            ++$i;
        }

        if (null === $key) {
            throw new ProductNotInCartException();
        }

        $this->items->remove($key);
        $this->productCount -= $prevQuantity;
        $this->record(new CartContentChanged($this->cartId));
    }

    /**
     * Update product of the cart.
     *
     * @param ProductId $productId the product id to update
     * @param int       $quantity  the new quantity
     *
     * @throws ProductNotInCartException if the product is not in the cart
     */
    public function updateProduct(ProductId $productId, int $quantity): void
    {
        $key = null;
        $i = 0;
        foreach ($this->items as $item) {
            if ($item->productId()->equals($productId)) {
                $key = $i;
                $prevQuantity = $item->quantity();
                $item->setQuantity($quantity);
            }
            ++$i;
        }
        if (null === $key) {
            throw new ProductNotInCartException();
        }

        $quantityDiff = $quantity - $prevQuantity;
        $this->productCount += $quantityDiff;
        $this->record(new CartContentChanged($this->cartId));
    }

    /**
     * Remove all items from the cart.
     */
    public function empty(): void
    {
        $this->items->clear();
        $this->cartTotal = 0;
        $this->productCount = 0;
    }

    /**
     * Get the total price of the items in cart.
     *
     * @return float the total price
     */
    public function cartTotal(): float
    {
        return $this->cartTotal;
    }

    /**
     * Set the total price of the items in cart.
     *
     * @param float $cartTotal the total price
     */
    public function setCartTotal(float $cartTotal): void
    {
        $this->cartTotal = $cartTotal;
    }

    /**
     * Get the item count in the cart.
     *
     * @return int the item count
     */
    public function productCount(): int
    {
        return $this->productCount;
    }
}
