<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Symfony\Component\Uid\Uuid;

/**
 * Service for add a product to the cart.
 */
class AddProductServiceCmd extends CartService
{
    /**
     * Add a product to the cart.
     *
     * @param UserId            $userId  the user id of the current user who owns the cart
     * @param AddProductRequest $request the request for add a product to the cart
     */
    public function execute(UserId $userId, AddProductRequest $request): void
    {
        $productId = new ProductId(new Uuid($request->id()));
        $quantity = $request->quantity();

        $this->findProductOrFail($productId);

        $cart = $this->findCartOrFail($userId);

        $cart->addProduct($productId, $quantity);

        $this->cartRepository->save($cart);
    }
}
