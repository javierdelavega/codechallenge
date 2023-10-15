<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductService;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddProductServiceTest extends KernelTestCase
{
  private $addProductService;
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineProductRepository;
  private $addProductRequest;
  private $invalidAddProductRequest;
  private $product;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->addProductService = $container->get(AddProductService::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineUserFactory = $container->get(DoctrineUserFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));

    $this->doctrineProductRepository->save($this->product);
    $this->addProductRequest = new AddProductRequest($this->product->id(), 2);
    $this->invalidAddProductRequest = new AddProductRequest(new ProductId(), 2);
    $this->user = $this->doctrineUserFactory->guestUser()->build(new UserId());
    $this->doctrineUserRepository->save($this->user);
    
  }

  /** @test */
  public function canAddProduct()
  {
    $added = false;
    $this->addProductService->execute($this->user->id(), $this->addProductRequest);

    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $items = $cart->items();

    foreach($items as $item) {
      if ($item->productId()->equals($this->product->id())) { $added = true;}
    }

    $this->assertTrue($added);
  }

  /** @test */
  public function canNotAddNotNotExistingproduct()
  {
    $this->expectException(ProductDoesNotExistException::class);
    $this->addProductService->execute($this->user->id(), $this->invalidAddProductRequest);

    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $items = $cart->items();

    $this->assertTrue($items->isEmpty());
  }

  /** @test */
  public function notExistingUserCanNotAddProduct()
  {
    $this->expectException(UserDoesNotExistException::class);
    $this->addProductService->execute(new UserId(), $this->addProductRequest);
  }

}