<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

use App\Codechallenge\Billing\Application\Exceptions\CartDoesNotExistException;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Billing\Domain\Model\Cart\CartContentChanged;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use App\Codechallenge\Shared\Domain\Event\DomainEventSubscriber;

/**
 * Service for calculate and update the total price of the items in the cart
 */
class UpdateCartTotalService implements DomainEventSubscriber
{
  private $cartRepository;
  private $productRepository;
  public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
  {
    $this->cartRepository = $cartRepository;
    $this->productRepository = $productRepository;
  }

  /**
   * Initializes the service subscribing to the DomainEventPublisher to listen for CartUpdated Domain events
   */
  public function initialize()
  {
    DomainEventPublisher::instance()->subscribe($this);
  }
  
  /**
   * Calculate the total price of the items in the cart
   * 
   * @param CartId $cartId the cart id to update the total price
   * @throws CartDoesNotExistException if the cart des ont exist
   */
  public function execute(CartId $cartId)
  {
    $cart = $this->cartRepository->cartOfId($cartId);

    if (null === $cart) { throw new CartDoesNotExistException(); }

    $items = $cart->items();
    $total = 0;

    foreach ($items as $item) {
      $product = $this->findProductOrFail($item->productId());
      $total += $product->price()->amount() * $item->quantity();
    }

    $cart->setCartTotal($total);
  }

  /**
   * Check if this service is susbscribed to a published domain event
   * 
   * @param DomainEvent $aDomainEvent
   * @see DomainEventSubscriber::isSubscribedTo()
   */
  public function isSubscribedTo($aDomainEvent)
  {
    return $aDomainEvent instanceof CartContentChanged ;
  }

  /**
   * Handles the domain event received: call execute() to calculate the total price of the cart
   * 
   * @param CartContentChanged $aDomainEvent the domain event
   */
  public function handle($aDomainEvent)
  {
    $this->execute($aDomainEvent->cartId());
  }

  /**
   * Find a product for the given product id
   * @param ProductId $productId the product id
   * @throws ProductDoesNotExistException
   */
  private function findProductOrFail(ProductId $productId)
  {
      $product = $this->productRepository->productOfId($productId);
      if (null === $product) {
          throw new ProductDoesNotExistException();
      }
      
      return $product;
  }

  
}