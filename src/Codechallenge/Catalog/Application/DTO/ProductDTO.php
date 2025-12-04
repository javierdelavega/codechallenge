<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Application\DTO;

/**
 * Data Transfer Object for delivery product data from Domain layer to Application layer.
 */
readonly class ProductDTO
{
    /**
     * Constructor.
     *
     * @param string $id          the product id
     * @param string $reference   the product reference
     * @param string $name        the product name
     * @param string $description the product description
     * @param float  $price       the product price
     */
    public function __construct(
        public string $id,
        public string $reference,
        public string $name,
        public string $description,
        public float $price
    ) {
    }
}
