<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Symfony\Component\Uid\Uuid;

/**
 * Service to remove a item from the cart.
 */
class RemoveProductService extends CartService
{
    /**
     * Remove a item from the cart.
     */
    public function execute(UserId $userId, RemoveProductRequest $request)
    {
        $productId = new ProductId(new Uuid($request->id));
        $this->findProductOrFail($productId);

        $cart = $this->findCartOrFail($userId);

        $cart->removeProduct($productId);

        $this->cartRepository->save($cart);
    }
}
