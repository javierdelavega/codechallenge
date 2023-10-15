<?php

namespace App\Codechallenge\Catalog\Application\Service;

use App\Codechallenge\Catalog\Application\DTO\ProductDTO;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;

/**
 * Service for get a list of products from the catalog
 */
class ListProductsService
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
   * Get a list of products from the catalog
   * 
   * @return Array the products list
   */
  public function execute() : Array
  {
    $productDTOs = array();
    $products = $this->productRepository->products();

    $i = 0;
    foreach ($products as $product) {
      $productDTOs[$i] = new ProductDTO($product);
      $i++;
    }
    
    return $productDTOs;

  }
}