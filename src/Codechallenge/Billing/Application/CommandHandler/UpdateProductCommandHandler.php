<?php

namespace App\Codechallenge\Billing\Application\CommandHandler;

use App\Codechallenge\Billing\Application\Command\UpdateProductCommand;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use Symfony\Component\Uid\Uuid;

/**
 * Handles the UpdateProductCommand.
 */
class UpdateProductCommandHandler extends CartService
{
    /**
     * @param UpdateProductCommand $command to update a product of the cart
     */
    public function __invoke(UpdateProductCommand $command): void
    {
        $productId = new ProductId(new Uuid($command->productId));
        $quantity = $command->quantity;

        $this->findProductOrFail($productId);

        $cart = $this->findCartOrFail($command->userId);

        $cart->updateProduct($productId, $quantity);

        DomainEventPublisher::instance()->publishAll($cart->releaseEvents());
        $this->cartRepository->save($cart);
    }
}
