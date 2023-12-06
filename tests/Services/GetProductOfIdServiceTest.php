<?php

namespace App\Tests\Services;

use App\Codechallenge\Catalog\Application\DTO\ProductDTO;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Application\Service\GetProductOfIdService;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetProductOfIdServiceTest extends KernelTestCase
{
  private $getProductOfIdService;
  private $productRepository;
  private $product;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->getProductOfIdService = $container->get(GetProductOfIdService::class);
    $this->productRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(30.60, new Currency("EUR")));

  }

  /** @test */
  public function canGetExistingProductInformation()
  {
    $this->productRepository->save($this->product);

    $productDTO = $this->getProductOfIdService->execute($this->product->id());

    $this->assertInstanceOf(ProductDTO::class, $productDTO);
    $this->assertEquals($productDTO->id, $this->product->id()->id());
  }

  /** @test */
  public function canNotGetNotExistingProductInformation()
  {

    $this->expectException(ProductDoesNotExistException::class);
    $productDTO = $this->getProductOfIdService->execute($this->product->id());

    $this->assertNull($productDTO);
  }

}