<?php

namespace App\Tests\Services;

use App\Codechallenge\Catalog\Application\Service\ListProductsService;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ListProductsServiceTest extends KernelTestCase
{
  private $listProductsService;
  private $productRepository;
  private $product;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->listProductsService = $container->get(ListProductsService::class);
    $this->productRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(30.60, new Currency("EUR")));
  }

  /** @test */
  public function canGetListOfProductsFromCatalog()
  {
    $this->productRepository->save($this->product);
    $products = $this->listProductsService->execute();

    $this->assertEquals(1, count($products));
  }

}