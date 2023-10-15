<?php

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Cart Model
 * The users have one cart
 * Users can add products to the cart
 * Registered users can confirm the cart to place an order
 */
class Cart
{
  private CartId $cartId;
  private UserId $userId;
  private Collection $items;
  private $productCount;
  private $cartTotal;

  /**
   * Constructor
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
   * Gets the cart id
   * 
   * @return CartId the cart id
   */
  public function id() : CartId
  {
    return $this->cartId;
  }

  /**
   * Get the cart items
   * 
   * @return Collection the items in the cart
   */
  public function items() : Collection
  {
    $this->items->toArray();
    return $this->items;
  }

  /**
   * Get the userId who owns the cart
   * 
   * @return UserId the user id
   */
  public function userId() : UserId
  {
    return $this->userId;
  }

  /**
   * Add product to the cart
   * 
   * @param ProductId $productId the product id
   * @param int $quantity the quantity
   */
  public function addProduct(ProductId $productId, $quantity)
  {
    $alReadyInCart = false;
    $prevQuantity = 0;

    // if the product is already in cart, update it
    foreach($this->items as $item) {
      if ($item->productId()->equals($productId)) {
        $prevQuantity = $item->quantity();
        $alReadyInCart = true;
      }
    }

    if ($alReadyInCart) {
      $this->updateProduct($productId, $quantity+$prevQuantity);
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
      $this->publishCartUpdatedEvent();
    }
  }

  /** 
   * Remove product from the cart
   * 
   * @param ProductId $productId the product id to remove
   * @throws ProductNotInCartException if the product is not in the cart
   */
  public function removeProduct(ProductId $productId)
  {
    $key = null;
    $i = 0;
    $this->items->toArray();
    foreach ($this->items as $item) {
      if ($item->productId()->equals($productId)) {
        $key = $i; 
        $prevQuantity = $item->quantity();
      }
      $i++;
    }

    if(null === $key) throw new ProductNotInCartException();

    $this->items->remove($key);
    $this->productCount-= $prevQuantity;
    $this->publishCartUpdatedEvent();
  }

  /** 
   * Update product of the cart
   * 
   * @param ProductId $productId the product id to update
   * @param int $quantity the new quantity
   * @throws ProductNotInCartException if the product is not in the cart
   */
  public function updateProduct(ProductId $productId, $quantity)
  {
    $key = null;
    $i = 0;
    foreach ($this->items as $item) {
      if ($item->productId()->equals($productId)) {
        $key = $i;
        $prevQuantity = $item->quantity();
        $item->setQuantity($quantity);
      }
      $i++;
    }
    if(null === $key) throw new ProductNotInCartException();
    
    $quantityDiff = $quantity - $prevQuantity;
    $this->productCount += $quantityDiff;
    $this->publishCartUpdatedEvent();
  }

  /**
   * Remove all items from the cart
   */
  public function empty()
  {
    $this->items->clear();
    $this->cartTotal = 0;
    $this->productCount = 0;
  }

  /**
   * Get the total price of the items in cart
   * 
   * @return float the total price
   */
  public function cartTotal() : float
  {
    return $this->cartTotal;
  }

  /**
   * Set the total price of the items in cart
   * 
   * @param float $cartTotal the total price
   */
  public function setCartTotal($cartTotal)
  {
    $this->cartTotal = $cartTotal;
  }

  /**
   * Get the item count in the cart
   * 
   * @return int the item count
   */
  public function productCount() : int
  {
    return $this->productCount;
  }

  /**
   * Publish a CartContentChanged Domain event
   */
  protected function publishCartUpdatedEvent()
  {
    DomainEventPublisher::instance()->publish(new CartContentChanged($this->cartId)); 
  }

}