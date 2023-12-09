<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Billing\Application\Command\EmptyCartCommand;
use App\Codechallenge\Billing\Application\Exceptions\CartIsEmptyException;
use App\Codechallenge\Billing\Application\Exceptions\UserNotRegisteredException;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Billing\Domain\Model\Order\Order;
use App\Codechallenge\Billing\Domain\Model\Order\OrderRepository;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;

/**
 * Service for create an order from a cart
 * Retrieve the items in the cart (ProductId and Quantity)
 * Retrieve the products information
 * Retrieve the user information
 * Create a new order for the user containing the products with current price.
 */
class CreateOrderFromCartService
{
    /**
     * Constructor.
     *
     * @param OrderRepository   $orderRepository   the order repository object
     * @param CartRepository    $cartRepository    the cart repository object
     * @param ProductRepository $productRepository the product repository object
     * @param UserRepository    $userRepository    the user repository object
     */
    public function __construct(
        private OrderRepository $orderRepository,
        private CartRepository $cartRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository,
        private CommandBus $commandBus
    ) {
    }

    /**
     * Create a new order from the products contained in the cart of the given user.
     *
     * @param UserId $userId the user id of the current user who owns the cart
     *
     * @throws CartIsEmptyException if the cart is empty
     */
    public function execute(UserId $userId): void
    {
        $user = $this->userRepository->userOfId($userId);

        if (!$user->registered()) {
            throw new UserNotRegisteredException();
        }

        $cart = $this->cartRepository->cartOfUser($userId);

        $order = new Order($this->orderRepository->nextIdentity(), $cart->userId(), $user->address());

        $items = $cart->items();

        if ($items->isEmpty()) {
            throw new CartIsEmptyException();
        }

        foreach ($items as $item) {
            $product = $this->productRepository->productOfId($item->productId());
            $order->addLine(
                $item->productId(),
                $item->quantity(),
                $product->price()->amount()
            );
        }

        $this->orderRepository->save($order);
        $this->commandBus->dispatch(new EmptyCartCommand($user->id()));
    }
}
