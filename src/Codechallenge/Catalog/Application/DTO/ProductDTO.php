<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Application\DTO;

use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Data Transfer Object for delivery product data from Domain layer to Application layer.
 */
class ProductDTO
{
    private ProductId $productId;
    private string $reference;
    private string $name;
    private string $description;
    private float $price;

    /**
     * Constructor.
     *
     * @param Product $product the product to represent
     */
    public function __construct(Product $product)
    {
        $this->productId = $product->id();
        $this->reference = $product->reference();
        $this->name = $product->name();
        $this->description = $product->description();
        $this->price = $product->price()->amount();
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
     * @return string the product reference
     */
    public function reference(): string
    {
        return $this->reference;
    }

    /**
     * Get the product name.
     *
     * @return string the product name
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
     * @return float the product price
     */
    public function price(): float
    {
        return $this->price;
    }
}
