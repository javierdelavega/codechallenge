<?php

namespace App\Tests\Entities;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use PHPUnit\Framework\TestCase;


class CartTest extends TestCase
{
  private $cartId;
  private $userId;

  protected function setUp() : void
  {
    $this->cartId = new CartId();
    $this->userId = new UserId();
  }
  /** @test */
  public function returnsCartId()
  {
    $cart = new Cart($this->cartId, $this->userId);

    $this->assertEquals($this->cartId, $cart->id());
  }

  /** @test */
  public function returnsUserId()
  {
    $cart = new Cart($this->cartId, $this->userId);

    $this->assertEquals($this->userId, $cart->userId());
  }

  /** @test */
  public function canAddNewProductToCart()
  {
    $added = false;
    $cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();
    $quantity = 1;

    $cart->addProduct($productId, $quantity);
    $items = $cart->items();

    foreach($items as $item) {
      if ($item->productId()->equals($productId)) $added = true;
    }

    $this->assertTrue($added);
  }

  /** @test */
  public function canAddAlreadyAddedProductToCart()
  {
    $added = false;
    $cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();
    $quantity = 1;

    $cart->addProduct($productId, $quantity);
    $cart->addProduct($productId, $quantity);

    $items = $cart->items();

    foreach($items as $item) {
      if ($item->productId()->equals($productId) && $item->quantity() == 2) $added = true;
    }

    $this->assertTrue($added);
  }

  /** @test */
  public function canRemoveAddedProductFromCart()
  {
    $added = false;
    $cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();
    $quantity = 1;

    $cart->addProduct($productId, $quantity);
    $items = $cart->items();

    foreach($items as $item) {
      if ($item->productId()->equals($productId)) $added = true;
    }
    $this->assertTrue($added);

    $cart->removeProduct($productId);
    $items = $cart->items();

    $added = false;
    foreach($items as $item) {
      if ($item->productId()->equals($productId)) $added = true;
    }
    $this->assertFalse($added);
  }

  /** @test */
  public function canNotRemoveNotAddedProductFromCart()
  {
    $cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();

    $this->expectException(ProductNotInCartException::class);
    $cart->removeProduct($productId);

    $this->assertEquals(0, $cart->productCount());
  }

  /** @test */
  public function canGetTheProductCount()
  {
    $cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();
    $quantity = 7;

    $cart->addProduct($productId, $quantity);

    $this->assertEquals($quantity, $cart->productCount());
  }

  /** @test */
  public function canEmptyCart()
  {
    $cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();
    $quantity = 7;

    $cart->addProduct($productId, $quantity);
    $items = $cart->items();

    $this->assertEquals($quantity, $cart->productCount());
    $this->assertFalse($items->isEmpty());

    $cart->empty();
    $this->assertEquals(0, $cart->productCount());
    $this->assertTrue($items->isEmpty());
  }

  /** @test */
  public function canSetAndGetTotal()
  {
    $total = 24.56;
    $cart = new Cart($this->cartId, $this->userId);

    $cart->setCartTotal($total);

    $this->assertEquals($total, $cart->cartTotal());
  }

}