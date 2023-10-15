<?php

namespace App\Tests\Entities;

use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Domain\Model\Cart\Item;
use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use PHPUnit\Framework\TestCase;


class ItemTest extends TestCase
{
  private $itemId;
  private $cartId;
  private $productId;
  private $quantity;


  protected function setUp() : void
  {
    $this->cartId = new CartId();
    $this->itemId = new ItemId();
    $this->productId = new ProductId();
    $this->quantity = 1;
  }
  /** @test */
  public function returnsItemId()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);

    $this->assertEquals($this->itemId, $item->id());
  }

  /** @test */
  public function returnsCartId()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);

    $this->assertEquals($this->cartId, $item->cartId());
  }

  /** @test */
  public function returnsProductId()
  {
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);

    $this->assertEquals($this->productId, $item->productId());
  }

  /** @test */
  public function canSetAndGetQuantity()
  {
    $newQuantity = 5;
    $item = new Item($this->itemId, $this->cartId, $this->productId, $this->quantity);

    $item->setQuantity($newQuantity);

    $this->assertEquals($newQuantity, $item->quantity());

  }

}