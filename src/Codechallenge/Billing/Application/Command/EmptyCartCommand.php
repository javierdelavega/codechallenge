<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Command;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Shared\Domain\Bus\Command\Command;

/**
 * Command to empty the cart.
 */
final readonly class EmptyCartCommand implements Command
{
    /**
     * Constructor.
     *
     * @param UserId $userId The user id
     */
    public function __construct(
        public UserId $userId,
    ) {
    }
}
