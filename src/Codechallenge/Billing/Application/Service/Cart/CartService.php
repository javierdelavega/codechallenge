<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartFactory;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;

/**
 * Service for management of the security tokens for API access.
 */
abstract class CartService
{
    /**
     * Constructor.
     *
     * @param UserRepository         $userRepository         the user repository object
     * @param CartRepository         $cartRepository         the cart repository object
     * @param CartFactory            $cartFactory            the cart factory object
     * @param ProductRepository      $productRepository      the product repository object
     * @param UpdateCartTotalService $updateCartTotalService the update cart total service object
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected CartRepository $cartRepository,
        protected CartFactory $cartFactory,
        protected ProductRepository $productRepository,
        protected UpdateCartTotalService $updateCartTotalService,
    ) {
    }

    /**
     * Find a cart for the given user id
     * If the user does not have a cart creates a new one.
     *
     * @param UserId $userId the user id
     *
     * @throws UserDoesNotExistException if the user does not exist
     */
    protected function findCartOrFail(UserId $userId): Cart
    {
        $user = $this->userRepository->userOfId($userId);
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $cart = $this->cartRepository->cartOfUser($userId);

        if (null === $cart) {
            $cart = $this->cartFactory->ofUser($user->id())->build($this->cartRepository->nextIdentity());
            $this->cartRepository->save($cart);
        }

        return $cart;
    }

    /**
     * Find a product for the given product id.
     *
     * @param ProductId $productId the product id
     *
     * @throws ProductDoesNotExistException
     */
    protected function findProductOrFail(ProductId $productId)
    {
        $product = $this->productRepository->productOfId($productId);
        if (null === $product) {
            throw new ProductDoesNotExistException();
        }

        return $product;
    }
}
