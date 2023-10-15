<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Service for add a product to the cart.
 */
class AddProductService extends CartService
{
  /**
   * Add a product to the cart
   * 
   * @param UserId $userId the user id of the current user who owns the cart
   * @param AddProductRequest $request the request for add a product to the cart
   */
  public function execute(UserId $userId, AddProductRequest $request)
  {
    $productId = new ProductId($request->id());
    $quantity = $request->quantity();

    $this->findProductOrFail($productId);

    $cart = $this->findCartOrFail($userId);

    $cart->addProduct($productId, $quantity);

    $this->cartRepository->save($cart);
  }
}