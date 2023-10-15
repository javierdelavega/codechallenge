<?php

namespace App\Codechallenge\Catalog\Domain\Model;

use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * Repository for management of the products
 */
interface ProductRepository
{
  /**
   * Adds a product
   * 
   * @param Product $product
   */
  public function save(Product $product);

  /**
   * Removes a product
   * 
   * @param Product $product
   */
  public function remove(Product $product);

  /**
   * Get the lists of products from the catalog
   * 
   * @return Array the products from the catalog
   */
  public function products();

  /**
   * Retrieves a product of the given id
   * 
   * @param ProductId $productId the product id
   * @return Product the product
   */
  public function productOfId(ProductId $productId);

  /**
   * 
   * Retrieves a product with the given reference
   * 
   * @param string $reference the product reference
   * @return Product
   */
  public function productOfReference($reference);

  /**
   * Gets a new unique Product id
   * 
   * @return ProductId
   */
  public function nextIdentity();

}