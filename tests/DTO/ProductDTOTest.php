<?php

namespace App\Tests\DTO;

use App\Codechallenge\Catalog\Application\DTO\ProductDTO;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use PHPUnit\Framework\TestCase;

class ProductDTOTest extends TestCase
{
  private $product;
  private $productDTO;
  
  protected function setUp() : void
  {
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription",
                                new Money(24.60, new Currency("EUR")));
    $this->productDTO = new ProductDTO(
                              $this->product->id(),
                              $this->product->reference(),
                              $this->product->name(),
                              $this->product->description(),
                              $this->product->price()->amount()
                            );
  }

  /** @test */
  public function returnValidId()
  {
    $this->assertEquals($this->product->id()->id(), $this->productDTO->id);
  }

  /** @test */
  public function returnValidReference()
  {
    $this->assertEquals($this->product->reference(), $this->productDTO->reference);
  }

  /** @test */
  public function returnValidName()
  {
    $this->assertEquals($this->product->name(), $this->productDTO->name);
  }

  /** @test */
  public function returnValidDescription()
  {
    $this->assertEquals($this->product->description(), $this->productDTO->description);
  }

  /** @test */
  public function returnValidPrice()
  {
    $this->assertEquals($this->product->price()->amount(), $this->productDTO->price);
  }

}