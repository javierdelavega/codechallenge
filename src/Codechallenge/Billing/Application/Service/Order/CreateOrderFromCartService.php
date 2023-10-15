<?php

namespace App\Codechallenge\Billing\Application\Service\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Billing\Application\Exceptions\CartIsEmptyException;
use App\Codechallenge\Billing\Application\Exceptions\UserNotRegisteredException;
use App\Codechallenge\Billing\Application\Service\Cart\EmptyCartService;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Billing\Domain\Model\Order\Order;
use App\Codechallenge\Billing\Domain\Model\Order\OrderRepository;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;

/**
 * Service for create an order from a cart
 * Retrieve the items in the cart (ProductId and Quantity)
 * Retrieve the products information
 * Retrieve the user information
 * Create a new order for the user containing the products with current price
 */
class CreateOrderFromCartService
{
  private $orderRepository;
  private $cartRepository;
  private $productRepository;
  private $userRepository;
  private $emptyCartService;

  /**
   * Constructor
   * 
   * @param OrderRepository $orderRepository the order repository object
   * @param CartRepository $cartRepository the cart repository object
   * @param ProductRepository $productRepository the product repository object
   * @param UserRepository $userRepository the user repository object
   * @param EmptyCartService $emptyCartService the empty cart service object
   */
  public function __construct(OrderRepository $orderRepository, CartRepository $cartRepository,
                              ProductRepository $productRepository, UserRepository $userRepository,
                              EmptyCartService $emptyCartService)
  {
    $this->orderRepository = $orderRepository;
    $this->cartRepository = $cartRepository;
    $this->productRepository = $productRepository;
    $this->emptyCartService = $emptyCartService;
    $this->userRepository = $userRepository;
  }

  /**
   * Create a new order from the products contained in the cart of the given user
   * 
   * @param UserId $userId the user id of the current user who owns the cart
   * @throws CartIsEmptyException if the cart is empty
   */
  public function execute(UserId $userId)
  {
    $user = $this->userRepository->userOfId($userId);

    if (!$user->registered()) { throw new UserNotRegisteredException(); }

    $cart = $this->cartRepository->cartOfUser($userId);
    
    $order = new Order($this->orderRepository->nextIdentity(), $cart->userId(), $user->address());

    $items = $cart->items();

    if ($items->isEmpty()) {throw new CartIsEmptyException();}

    foreach ($items as $item) {
      $product = $this->productRepository->productOfId($item->productId());
      $order->addLine(
        $item->productId(),
        $item->quantity(),
        $product->price()->amount()
      );
    }

    $this->orderRepository->save($order);
    $this->emptyCartService->execute($userId);

  }
}