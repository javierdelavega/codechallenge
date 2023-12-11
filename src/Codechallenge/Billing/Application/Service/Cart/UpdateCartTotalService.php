<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Billing\Application\Exceptions\CartDoesNotExistException;
use App\Codechallenge\Billing\Domain\Model\Cart\CartContentChanged;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;
use App\Codechallenge\Shared\Domain\Event\DomainEvent;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use App\Codechallenge\Shared\Domain\Event\DomainEventSubscriber;

/**
 * Service for calculate and update the total price of the items in the cart.
 */
class UpdateCartTotalService implements DomainEventSubscriber
{
    public function __construct(
        private CartRepository $cartRepository,
        private ProductRepository $productRepository
    ) {
        $this->initialize();
    }

    /**
     * Initializes the service subscribing to the DomainEventPublisher to listen for CartUpdated Domain events.
     */
    private function initialize(): void
    {
        DomainEventPublisher::instance()->subscribe($this);
    }

    /**
     * Calculate the total price of the items in the cart.
     *
     * @param CartId $cartId the cart id to update the total price
     *
     * @throws CartDoesNotExistException if the cart des ont exist
     */
    public function execute(CartId $cartId): void
    {
        $cart = $this->cartRepository->cartOfId($cartId);

        if (null === $cart) {
            throw new CartDoesNotExistException();
        }

        $items = $cart->items();
        $total = 0;

        foreach ($items as $item) {
            $product = $this->findProductOrFail($item->productId());
            $total += $product->price()->amount() * $item->quantity();
        }

        $cart->setCartTotal($total);
    }

    /**
     * Check if this service is susbscribed to a published domain event.
     *
     * @param DomainEvent $aDomainEvent
     *
     * @return bool true if is suscribed. false if not suscribed.
     *
     * @see DomainEventSubscriber::isSubscribedTo()
     */
    public function isSubscribedTo($aDomainEvent): bool
    {
        return $aDomainEvent instanceof CartContentChanged;
    }

    /**
     * Handles the domain event received: call execute() to calculate the total price of the cart.
     *
     * @param CartContentChanged $aDomainEvent the domain event
     */
    public function handle($aDomainEvent): void
    {
        $this->execute($aDomainEvent->cartId());
    }

    /**
     * Find a product for the given product id.
     *
     * @param ProductId $productId the product id
     *
     * @return Product the product
     *
     * @throws ProductDoesNotExistException
     */
    private function findProductOrFail(ProductId $productId): Product
    {
        $product = $this->productRepository->productOfId($productId);
        if (null === $product) {
            throw new ProductDoesNotExistException();
        }

        return $product;
    }
}
