<?php

namespace App\Codechallenge\Billing\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;


/**
 * Repository for manage carts
 */
interface CartRepository
{
  /**
   * Adds a cart
   * 
   * @param Cart $cart
   */
  public function save(Cart $cart);

  /**
   * Removes a cart
   * 
   * @param Cart $cart
   */
  public function remove(Cart $cart);

  /**
   * Retrieves a cart of the given id
   * 
   * @param CartId the id of the cart
   * @return Cart the Cart with requested id
   */
  public function cartOfId(CartId $cartId);

  /**
   * Retrieves a cart of the given user id
   * 
   * @param UserId the id of the user
   * @return Cart
   */
  public function cartOfUser(UserId $userId);

  /**
   * Gets a new unique Cart id
   * 
   * @return CartId
   */
  public function nextIdentity();
}