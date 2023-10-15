<?php

namespace App\Tests\Repositories;

use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineProductRepositoryTest extends KernelTestCase
{
  private $doctrineProductRepository;
  private $product;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(30.60, new Currency("EUR")));

  }

  /** @test */
  public function canPersistProduct()
  {
    $this->doctrineProductRepository->save($this->product);

    $persistedProduct = $this->doctrineProductRepository->productOfId($this->product->id());

    $this->assertInstanceOf(Product::class, $persistedProduct);
  }

  /** @test */
  public function canGetProductOfId()
  {
    $this->doctrineProductRepository->save($this->product);

    $persistedProduct = $this->doctrineProductRepository->productOfId($this->product->id());

    $this->assertEquals($this->product, $persistedProduct);
  }

  /** @test */
  public function canGetProductOfReference()
  {
    $this->doctrineProductRepository->save($this->product);

    $persistedProduct = $this->doctrineProductRepository->productOfReference($this->product->reference());

    $this->assertEquals($this->product, $persistedProduct);
  }

  /** @test */
  public function canRemoveProduct()
  {
    $this->doctrineProductRepository->save($this->product);

    $persistedProduct = $this->doctrineProductRepository->productOfId($this->product->id());
    $this->assertEquals($this->product, $persistedProduct);

    $this->doctrineProductRepository->remove($this->product);
    $persistedProduct = $this->doctrineProductRepository->productOfId($this->product->id());
    $this->assertNull($persistedProduct);
  }

  /** @test */
  public function canGetListOfProducts()
  {
    $exists = false;
    $this->doctrineProductRepository->save($this->product);

    $persistedProducts = $this->doctrineProductRepository->products();

    foreach($persistedProducts as $persistedProduct) {
      if ($this->product->id()->equals($persistedProduct->id())) { $exists = true; }
    }

    $this->assertTrue($exists);
  }

  /** @test */
  public function canGetNewIidentity()
  {
    $id = $this->doctrineProductRepository->nextIdentity();

    $this->assertInstanceOf(ProductId::class, $id);
  }



}