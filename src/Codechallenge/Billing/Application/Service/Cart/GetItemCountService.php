<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

/**
 * Service to get the items count in the cart
 */
class GetItemCountService extends CartService
{
  /**
   * Get the items count in the cart
   * 
   * @param UserId $userId the user id of the current user who owns the cart
   * @return int the items count in the cart
   */
  public function execute(UserId $userId) : int
  {
    $cart = $this->findCartOrFail($userId);

    return $cart->productCount();
  }
}