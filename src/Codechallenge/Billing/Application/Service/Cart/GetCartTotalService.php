<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

/**
 * Service to get total price of the cart
 */
class GetCartTotalService extends CartService
{
  /**
   * Get the items count in the cart
   * 
   * @param UserId $userId the user id of the current user who owns the cart
   * @return float the cart total price
   */
  public function execute(UserId $userId) : float
  {
    $cart = $this->findCartOrFail($userId);

    return $cart->cartTotal();
  }
}