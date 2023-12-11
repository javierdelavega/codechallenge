<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\QueryHandler;

use App\Codechallenge\Billing\Application\DTO\ItemDTO;
use App\Codechallenge\Billing\Application\Query\GetItemsQuery;
use App\Codechallenge\Billing\Application\Service\Cart\CartService;

class GetItemsQueryHandler extends CartService
{
    public function __invoke(GetItemsQuery $query): array
    {
        $cart = $this->findCartOrFail($query->userId);

        $items = $cart->items();

        $itemDTOs = [];
        $i = 0;
        foreach ($items as $item) {
            $product = $this->findProductOrFail($item->productId());
            $itemDTOs[$i] = new ItemDTO(
                $item->id(),
                $product->id()->__toString(),
                $product->reference(),
                $product->name(),
                $product->description(),
                $product->price()->amount(),
                $item->quantity()
            );
            ++$i;
        }

        return $itemDTOs;
    }
}
