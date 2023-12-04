<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Cart;

/**
 * Request for remove a product from the cart.
 */
class RemoveProductRequest
{
    private string $id;

    /**
     * Constructor.
     *
     * @param string $id the product id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Get the product id.
     *
     * @return string the product id
     */
    public function id(): string
    {
        return $this->id;
    }
}
