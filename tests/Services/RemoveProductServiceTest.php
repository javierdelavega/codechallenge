<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Billing\Application\Service\Cart\GetItemCountService;
use App\Codechallenge\Billing\Application\Service\Cart\RemoveProductService;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RemoveProductServiceTest extends KernelTestCase
{
  private $removeProductService;
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineProductRepository;
  private $doctrineCartFactory;
  private $user;
  private $product;
  private $anotherProduct;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->removeProductService = $container->get(RemoveProductService::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineUserFactory = $container->get(DoctrineUserFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineCartFactory = $container->get(DoctrineCartFactory::class);
    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);

    $this->user = $this->doctrineUserFactory->guestUser()->build(new UserId());
    $this->doctrineUserRepository->save($this->user);

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));

    $this->doctrineProductRepository->save($this->product);

    $this->anotherProduct = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));

    $this->doctrineProductRepository->save($this->anotherProduct);
  }

  /** @test */
  public function canRemoveProduct()
  {
    $quantity = 5;
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->addProduct($this->product->id(), $quantity);
    $this->doctrineCartRepository->save($cart);
    $this->assertEquals($quantity, $cart->productCount());

    $this->removeProductService->execute($this->user->id(), $this->product->id());

    $this->assertEquals(0, $cart->productCount());

  }

  /** @test */
  public function canNotRemoveNotAddedproduct()
  {
    $quantity = 5;
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->addProduct($this->anotherProduct->id(), $quantity);
    $this->doctrineCartRepository->save($cart);
    $this->assertEquals($quantity, $cart->productCount());
    
    $this->expectException(ProductNotInCartException::class);
    $this->removeProductService->execute($this->user->id(), $this->product->id());

    $this->assertEquals($quantity, $cart->productCount());
    
  }

}