<?php

namespace App\Codechallenge\Catalog\Application\Service;

use App\Codechallenge\Catalog\Application\DTO\ProductDTO;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;

/**
 * Service for get a product from the given id
 */
class GetProductOfIdService
{
  private $productRepository;

  /**
   * Constructor
   * 
   * @param ProductRepository $productRepository the products repository
   */
  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }


  /**
   * Get a product of the given id
   * 
   * @param ProductId $productId the product id
   * @throws ProductDoesNotExistException if the product does not exist
   * @return ProductDTO the product
   */
  public function execute(ProductId $productId) : ProductDTO
  {
    $product = $this->productRepository->productOfId($productId);

    if (null === $product) {
      throw new ProductDoesNotExistException();
    }

    $productDTO = new ProductDTO($product);
    
    return $productDTO;

  }
}