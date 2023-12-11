<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Domain\Model;

/**
 * Product Model represents a product of the catalog.
 */
class Product
{
    /**
     * Constructor.
     *
     * @param ProductId $productId   the product id
     * @param string    $reference   the product reference
     * @param string    $name        the product name
     * @param string    $description the product description
     * @param Money     $price       the price of the product
     */
    public function __construct(
        private ProductId $productId,
        private string $reference,
        private string $name,
        private string $description,
        private Money $price
    ) {
    }

    /**
     * Get the product id.
     *
     * @return ProductId the product id
     */
    public function id(): ProductId
    {
        return $this->productId;
    }

    /**
     * Get the product reference.
     *
     * @return string the reference
     */
    public function reference(): string
    {
        return $this->reference;
    }

    /**
     * Get the product name.
     *
     * @return string the name
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the product description.
     *
     * @return string the product description
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Get the product price.
     *
     * @return Money the product price
     */
    public function price(): Money
    {
        return $this->price;
    }
}
