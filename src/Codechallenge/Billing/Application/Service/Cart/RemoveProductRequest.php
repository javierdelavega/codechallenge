<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

/**
 * Request for remove a product from the cart.
 */
readonly class RemoveProductRequest
{
    /**
     * Constructor.
     *
     * @param string $id the product id
     */
    public function __construct(
        public string $id,
    ) {
    }
}
