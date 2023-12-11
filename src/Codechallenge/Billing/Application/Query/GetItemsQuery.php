<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Query;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Shared\Domain\Bus\Query\Query;

final readonly class GetItemsQuery implements Query
{
    /**
     * Constructor.
     *
     * @param UserId $userId the user id who request the total
     */
    public function __construct(
        public UserId $userId,
    ) {
    }
}
