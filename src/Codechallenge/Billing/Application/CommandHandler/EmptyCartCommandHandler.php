<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\CommandHandler;

use App\Codechallenge\Billing\Application\Command\EmptyCartCommand;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

/**
 * Handles the EmptyCartCommand.
 */
class EmptyCartCommandHandler extends CartService
{
    /**
     * @param EmptyCartCommand $command to empty the cart
     */
    public function __invoke(EmptyCartCommand $command)
    {
        $cart = $this->findCartOrFail($command->userId);

        $cart->empty();

        $this->cartRepository->save($cart);
    }
}
