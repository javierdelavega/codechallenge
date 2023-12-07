<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

/**
 * Request for update a cart product a user.
 */
class UpdateProductRequest
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
