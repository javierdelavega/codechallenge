<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Service form update a item in the cart
 */
class UpdateProductService extends CartService
{
  /**
   * Update the item in the cart
   * 
   * @param UserId $userId the user id of the current user who owns the cart
   * @param UpdateProductRequest $request the request to update the item in the cart
   */
  public function execute(UserId $userId, UpdateProductRequest $request)
  {
    $productId = new ProductId($request->id());
    $quantity = $request->quantity();

    $this->findProductOrFail($productId);
    
    $cart = $this->findCartOrFail($userId);

    $cart->updateProduct($productId, $quantity);

    $this->cartRepository->save($cart);
  }
}