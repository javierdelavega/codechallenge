<?php

declare(strict_types=1);

namespace App\Tests\Commands;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Command\AddProductCommand;
use App\Codechallenge\Billing\Application\Command\RemoveProductCommand;
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

class RemoveProductCommandTest extends KernelTestCase
{
  private CommandBus $commandBus;
  private RemoveProductCommand $removeProductCommand;
  private RemoveProductCommand $invalidRemoveProductCommand;
  private AddProductCommand $addProductCommand;
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

    $this->commandBus = $container->get(CommandBus::class);
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

    $this->addProductCommand = new AddProductCommand($this->user->id(), $this->product->id()->__toString(), $this->quantity);
    $this->removeProductCommand = new RemoveProductCommand($this->user->id(), $this->product->id()->__toString());
    $this->invalidRemoveProductCommand = new RemoveProductCommand($this->user->id(), $this->anotherProduct->id()->__toString());
  }

  /** @test */
  public function canRemoveProduct()
  {
    
    $this->commandBus->dispatch($this->addProductCommand);
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $this->assertEquals($this->quantity, $cart->productCount());

    $this->commandBus->dispatch($this->removeProductCommand);

    $this->assertEquals(0, $cart->productCount());

  }

  /** @test */
  public function canNotRemoveNotAddedproduct()
  {
    $this->commandBus->dispatch($this->addProductCommand);
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $this->assertEquals($this->quantity, $cart->productCount());
    
    $this->expectException(ProductNotInCartException::class);
    $this->commandBus->dispatch($this->invalidRemoveProductCommand);

    $this->assertEquals($this->quantity, $cart->productCount());
    
  }

}