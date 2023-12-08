<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\CommandHandler;

use App\Codechallenge\Billing\Application\Command\EmptyCartCommand;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

class EmptyCartCommandHandler extends CartService
{

  public function __invoke(EmptyCartCommand $command)
  {
    $cart = $this->findCartOrFail($command->userId);

        $cart->empty();

        $this->cartRepository->save($cart);
  }
  
}