<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Command;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Shared\Domain\Bus\Command\Command;

/**
 * Command to update a product of the cart.
 */
final readonly class UpdateProductCommand implements Command
{
    /**
     * Constructor.
     *
     * @param UserId $userId    The user id
     * @param string $productId the productId to update
     * @param int    $quantity  the new quantity
     */
    public function __construct(
        public UserId $userId,
        public string $productId,
        public int $quantity
    ) {
    }
}
