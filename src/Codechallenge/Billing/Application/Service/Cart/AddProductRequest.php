<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

/**
 * Request for add a product to the cart.
 */
readonly class AddProductRequest
{
    /**
     * Constructor.
     *
     * @param string $id       the product id
     * @param int    $quantity quantity
     */
    public function __construct(
        public string $id,
        public int $quantity
    ) {
    }
}
