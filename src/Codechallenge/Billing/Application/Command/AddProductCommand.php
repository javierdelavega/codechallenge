<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Command;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Shared\Domain\Bus\Command\Command;

/**
 * Command to add product to the cart.
 */
final readonly class AddProductCommand implements Command
{
    /**
     * Constructor.
     *
     * @param UserId $userId    The user id
     * @param string $productId the product id
     * @param int    $quantity  the product quantity
     */
    public function __construct(
        public UserId $userId,
        public string $productId,
        public int $quantity
    ) {
    }
}
