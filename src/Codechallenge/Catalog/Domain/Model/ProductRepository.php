<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Domain\Model;

/**
 * Repository for management of the products.
 */
interface ProductRepository
{
    /**
     * Adds a product.
     */
    public function save(Product $product);

    /**
     * Removes a product.
     */
    public function remove(Product $product);

    /**
     * Get the lists of products from the catalog.
     *
     * @return array the products from the catalog
     */
    public function products();

    /**
     * Retrieves a product of the given id.
     *
     * @param ProductId $productId the product id
     *
     * @return Product the product
     */
    public function productOfId(ProductId $productId);

    /**
     * Retrieves a product with the given reference.
     *
     * @param string $reference the product reference
     *
     * @return Product
     */
    public function productOfReference(string $reference);

    /**
     * Gets a new unique Product id.
     *
     * @return ProductId
     */
    public function nextIdentity();
}
