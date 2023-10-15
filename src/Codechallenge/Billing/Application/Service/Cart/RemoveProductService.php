<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Service to remove a item from the cart
 */
class RemoveProductService extends CartService
{
  /**
   * Remove a item from the cart
   * 
   * @param UserId $userid the user id of the current user who owns the cart
   * @param ProductId $productId the id of the product to remove from the cart
   */
  public function execute(UserId $userId, $productId)
  {
    $productId = new ProductId($productId);
    $this->findProductOrFail($productId);

    $cart = $this->findCartOrFail($userId);

    $cart->removeProduct($productId);

    $this->cartRepository->save($cart);
  }
}