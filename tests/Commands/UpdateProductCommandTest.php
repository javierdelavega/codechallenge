<?php

namespace App\Tests\Commands;


use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Command\UpdateProductCommand;
use App\Codechallenge\Billing\Application\Exceptions\ProductNotInCartException;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UpdateProductCommandTest extends KernelTestCase
{
  private $commandBus;
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineCartFactory;
  private $doctrineProductRepository;
  private $updateProductCommand;
  private $invalidUpdateProductCommand;
  private $product;
  private $anotherProduct;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->commandBus = $container->get(CommandBus::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineUserFactory = $container->get(DoctrineUserFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineCartFactory = $container->get(DoctrineCartFactory::class);
    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));

    $this->doctrineProductRepository->save($this->product);
    $this->anotherProduct = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));

    $this->doctrineProductRepository->save($this->anotherProduct);
    $this->user = $this->doctrineUserFactory->guestUser()->build(new UserId());
    $this->doctrineUserRepository->save($this->user);
    $this->updateProductCommand = new UpdateProductCommand($this->user->id(), $this->product->id(), 2);
    $this->invalidUpdateProductCommand = new UpdateProductCommand($this->user->id(), $this->anotherProduct->id(), 2);
    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    
  }

  /** @test */
  public function canUpdateProduct()
  {
    $quantity = 5;
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->addProduct($this->product->id(), $quantity);
    $this->doctrineCartRepository->save($cart);
    $this->assertEquals($quantity, $cart->productCount());

    $this->commandBus->dispatch($this->updateProductCommand);

    $this->assertEquals($this->updateProductCommand->quantity, $cart->productCount());
  }

  /** @test */
  public function canNotUpdateNotAddedProduct()
  {
    $quantity = 5;
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->addProduct($this->product->id(), $quantity);
    $this->doctrineCartRepository->save($cart);
    $this->assertEquals($quantity, $cart->productCount());

    $this->expectException(ProductNotInCartException::class);
    $this->commandBus->dispatch($this->invalidUpdateProductCommand);

    $this->assertEquals($quantity, $cart->productCount());
  }


}