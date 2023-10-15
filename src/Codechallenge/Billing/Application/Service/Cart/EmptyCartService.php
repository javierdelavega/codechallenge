<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

/**
 * Service for empty the cart
 */
class EmptyCartService extends CartService
{
  /**
   * Empty the cart
   * 
   * @param UserId $userId the user id of the current user who owns the cart 
   */
  public function execute(UserId $userId)
  {
    $cart = $this->findCartOrFail($userId);

    $cart->empty();

    $this->cartRepository->save($cart);
  }
}