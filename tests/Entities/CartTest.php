<?php

namespace App\Tests\Entities;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;


class CartTest extends KernelTestCase
{
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineCartFactory;
  private $user;
  private $cartId;
  private $userId;

  protected function setUp() : void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->userId = new UserId();
    $this->cartId = new CartId();
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineUserFactory = $container->get(DoctrineUserFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineCartFactory = $container->get(DoctrineCartFactory::class);

    $this->user = $this->doctrineUserFactory->guestUser()->build($this->userId);
    $this->doctrineUserRepository->save($this->user);

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build($this->cartId);
    $this->doctrineCartRepository->save($cart);

  }
  /** @test */
  public function returnsCartId()
  {
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

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
    //$cart = new Cart($this->cartId, $this->userId);
    $productId = new ProductId();
    $quantity = 1;

    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

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
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
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
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
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
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $productId = new ProductId();

    $this->expectException(ProductNotInCartException::class);
    $cart->removeProduct($productId);

    $this->assertEquals(0, $cart->productCount());
  }

  /** @test */
  public function canGetTheProductCount()
  {
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $productId = new ProductId();
    $quantity = 7;

    $cart->addProduct($productId, $quantity);

    $this->assertEquals($quantity, $cart->productCount());
  }

  /** @test */
  public function canEmptyCart()
  {
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
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
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->setCartTotal($total);

    $this->assertEquals($total, $cart->cartTotal());
  }

}