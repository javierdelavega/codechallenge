<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\DTO\ItemDTO;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

/**
 * Service to get the items in the cart
 */
class GetItemsService extends CartService
{
  /**
   * Get the items in the cart
   * 
   * @param UserId $userId the user id of the current user who owns the cart
   * @return Array the items in the cart
   */
  public function execute(UserId $userId) : Array
  {
    $cart = $this->findCartOrFail($userId);

    $items = $cart->items();

    $itemDTOs = array();
    $i = 0;
    foreach ($items as $item) {
      $product = $this->findProductOrFail($item->productId());
      $itemDTOs[$i] = new ItemDTO($item, $product);
      $i++;
    }

    return $itemDTOs;
  }
}