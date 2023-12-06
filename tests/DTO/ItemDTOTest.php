<?php

namespace App\Tests\DTO;

use App\Codechallenge\Billing\Application\DTO\ItemDTO;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Domain\Model\Cart\Item;
use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use PHPUnit\Framework\TestCase;

class ItemDTOTest extends TestCase
{
  private $itemId;
  private $cartId;
  private $productId;
  private $quantity;
  private $product;
  private $reference;
  private $name;
  private $description;
  private $price;
  
  protected function setUp() : void
  {
    $this->itemId = new ItemId();
    $this->cartId = new CartId();
    $this->productId = new ProductId();
    $this->quantity = 3;
    $this->reference = "testReference";
    $this->name = "testName";
    $this->description = "testDescription";
    $this->price = new Money(24.30, new Currency("EUR"));
    $this->product = new Product($this->productId, $this->reference, $this->name, 
                                $this->description, $this->price);
  }

  /** @test */
  public function returnValidItemId()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($item->id(), $itemDTO->id);
  }

  /** @test */
  public function returnValidProductId()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($item->productId()->id(), $itemDTO->productId);
  }

  /** @test */
  public function returnValidQuantity()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($item->quantity(), $itemDTO->quantity);
  }

  /** @test */
  public function returnValidProductReference()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($this->reference, $itemDTO->reference);
  }

  /** @test */
  public function returnValidProductName()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($this->name, $itemDTO->name);
  }

  /** @test */
  public function returnValidProductDescription()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($this->description, $itemDTO->description);
  }

  /** @test */
  public function returnValidProductPrice()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);
    $itemDTO = new ItemDTO(
      $item->id(), 
      $this->product->id(),
      $this->product->reference(),
      $this->product->name(),
      $this->product->description(),
      $this->product->price()->amount(),
      $item->quantity()
    );

    $this->assertEquals($this->price->amount(), $itemDTO->price);
  }

}