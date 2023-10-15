<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Billing\Domain\Model\Cart\CartFactory;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;

/**
 * Service for management of the security tokens for API access
 */
abstract class CartService
{
  protected $userRepository;
  protected $cartRepository;
  protected $cartFactory;
  protected $productRepository;

  /**
   * Constructor
   * 
   * @param UserRepository $userRepository the user repository object
   * @param CartRepository $apiTokenRepository the apitoken repository object
   */
  public function __construct(UserRepository $userRepository, CartRepository $cartRepository, 
                              CartFactory $cartFactory, ProductRepository $productRepository,
                              UpdateCartTotalService $updateCartTotalService)
  {
    $this->userRepository = $userRepository;
    $this->cartRepository = $cartRepository;
    $this->cartFactory = $cartFactory;
    $this->productRepository = $productRepository;
    $updateCartTotalService->initialize();
  }

  /**
   * Find a cart for the given user id
   * If the user does not have a cart creates a new one
   * 
   * @throws UserDoesNotExistException if the user does not exist
   */
  protected function findCartOrFail($userId)
  {
      $user = $this->userRepository->userOfId(new UserId($userId));
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
   * Find a product for the given product id
   * @param ProductId $productId the product id
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