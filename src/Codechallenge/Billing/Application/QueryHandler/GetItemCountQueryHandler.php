<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\QueryHandler;

use App\Codechallenge\Billing\Application\Query\GetItemCountQuery;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

class GetItemCountQueryHandler extends CartService
{
    public function __invoke(GetItemCountQuery $query): int
    {
        $cart = $this->findCartOrFail($query->userId);

        return $cart->productCount();
    }
}
