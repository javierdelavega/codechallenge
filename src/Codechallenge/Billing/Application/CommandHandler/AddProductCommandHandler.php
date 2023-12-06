<?php

namespace App\Codechallenge\Billing\Application\CommandHandler;

use App\Codechallenge\Billing\Application\Command\AddProductCommand;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Symfony\Component\Uid\Uuid;

class AddProductCommandHandler extends CartCommandHandler
{
    public function __construct() {}
   
    public function __invoke(AddProductCommand $command): void
    {
      $productId = new ProductId(new Uuid($command->request->id()));
        $quantity = $command->request->quantity();

        $this->findProductOrFail($productId);

        $cart = $this->findCartOrFail($command->userId);

        $cart->addProduct($productId, $quantity);

        $this->cartRepository->save($cart);
    }
}