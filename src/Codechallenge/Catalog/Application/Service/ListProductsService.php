<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Application\Service;

use App\Codechallenge\Catalog\Application\DTO\ProductDTO;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;

/**
 * Service for get a list of products from the catalog.
 */
class ListProductsService
{
    /**
     * Constructor.
     *
     * @param ProductRepository $productRepository the products repository
     */
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * Get a list of products from the catalog.
     *
     * @return array the products list
     */
    public function execute(): array
    {
        $productDTOs = [];
        $products = $this->productRepository->products();

        $i = 0;
        foreach ($products as $product) {
            $productDTOs[$i] = new ProductDTO(
                $product->id()->__toString(),
                $product->reference(),
                $product->name(),
                $product->description(),
                $product->price()->amount()
            );
            ++$i;
        }

        return $productDTOs;
    }
}
