<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\QueryHandler;

use App\Codechallenge\Billing\Application\Query\GetCartTotalQuery;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

class GetCartTotalQueryHandler extends CartService
{
    public function __invoke(GetCartTotalQuery $query): float
    {
        $cart = $this->findCartOrFail($query->userId);

        return $cart->cartTotal();
    }
}
