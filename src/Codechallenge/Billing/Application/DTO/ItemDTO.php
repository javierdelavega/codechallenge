<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\DTO;

use App\Codechallenge\Billing\Domain\Model\Cart\Item;
use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Data Transfer Object for delivery cart item data from Domain layer to Application layer.
 */
class ItemDTO
{
    /**
     * Constructor.
     *
     * @param ProductId $productId   the cart product id
     * @param string    $reference   the cart product reference
     * @param string    $name        the cart product name
     * @param string    $description the cart product description
     * @param string    $description the cart product description
     * @param float     $price       the cart item price
     */
    public function __construct(
        public ItemId $id,
        public ProductId $productId,
        public string $reference,
        public string $name,
        public string $description,
        public float $price,
        public int $quantity
    ) {
    }
}
