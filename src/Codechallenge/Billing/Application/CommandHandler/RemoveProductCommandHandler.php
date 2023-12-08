<?php

namespace App\Codechallenge\Billing\Application\CommandHandler;

use App\Codechallenge\Billing\Application\Command\RemoveProductCommand;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use Symfony\Component\Uid\Uuid;

class RemoveProductCommandHandler extends CartService
{
    public function __invoke(RemoveProductCommand $command): void
    {
        $productId = new ProductId(new Uuid($command->productId));
        $this->findProductOrFail($productId);

        $cart = $this->findCartOrFail($command->userId);

        $cart->removeProduct($productId);

        DomainEventPublisher::instance()->publishAll($cart->releaseEvents());
        $this->cartRepository->save($cart);
    }
}
