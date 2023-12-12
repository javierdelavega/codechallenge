<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Command;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Shared\Domain\Bus\Command\Command;

/**
 * Command to remove product from the cart.
 */
final readonly class RemoveProductCommand implements Command
{
    /**
     * Constructor.
     *
     * @param UserId $userId    The user id
     * @param string $productId the product id to add to the cart
     */
    public function __construct(
        public UserId $userId,
        public string $productId,
    ) {
    }
}
