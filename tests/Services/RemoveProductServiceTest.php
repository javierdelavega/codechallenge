<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductService;
use App\Codechallenge\Billing\Application\Service\Cart\GetItemCountService;
use App\Codechallenge\Billing\Application\Service\Cart\RemoveProductRequest;
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
  private $removeProductRequest;
  private $invalidRemoveProductRequest;
  private $addProductService;
  private $addProductRequest;
  private $quantity;
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

    $this->addProductService = $container->get(AddProductService::class);
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

    $this->quantity = 5;

    $this->doctrineProductRepository->save($this->product);

    $this->anotherProduct = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));

    $this->doctrineProductRepository->save($this->anotherProduct);

    $this->addProductRequest = new AddProductRequest($this->product->id()->__toString(), $this->quantity);
    $this->removeProductRequest = new RemoveProductRequest($this->product->id()->__toString());
    $this->invalidRemoveProductRequest = new RemoveProductRequest($this->anotherProduct->id()->__toString());
  }

  /** @test */
  public function canRemoveProduct()
  {
    
    $this->addProductService->execute($this->user->id(), $this->addProductRequest);
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $this->assertEquals($this->quantity, $cart->productCount());

    $this->removeProductService->execute($this->user->id(), $this->removeProductRequest);

    $this->assertEquals(0, $cart->productCount());

  }

  /** @test */
  public function canNotRemoveNotAddedproduct()
  {
    $this->addProductService->execute($this->user->id(), $this->addProductRequest);
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $this->assertEquals($this->quantity, $cart->productCount());
    
    $this->expectException(ProductNotInCartException::class);
    $this->removeProductService->execute($this->user->id(), $this->invalidRemoveProductRequest);

    $this->assertEquals($this->quantity, $cart->productCount());
    
  }

}