<?php

namespace App\Codechallenge\Billing\Application\CommandHandler;

use App\Codechallenge\Billing\Application\Command\AddProductCommand;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use Symfony\Component\Uid\Uuid;

/**
 * Handles the AddProductCommand.
 */
class AddProductCommandHandler extends CartService
{
    /**
     * @param AddProductCommand $command the add product
     */
    public function __invoke(AddProductCommand $command): void
    {
        $productId = new ProductId(new Uuid($command->productId));
        $quantity = $command->quantity;

        $this->findProductOrFail($productId);

        $cart = $this->findCartOrFail($command->userId);

        $cart->addProduct($productId, $quantity);

        DomainEventPublisher::instance()->publishAll($cart->releaseEvents());
        $this->cartRepository->save($cart);
    }
}
