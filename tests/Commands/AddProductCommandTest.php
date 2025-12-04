<?php

namespace App\Tests\Commands;

use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Command\AddProductCommand;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Catalog\Application\Exceptions\ProductDoesNotExistException;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddProductCommandTest extends KernelTestCase
{
  private CommandBus $commandBus;
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineProductRepository;
  private $addProductCommand;
  private $invalidAddProductCommand;
  private $product;
  private $invalidProductId;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->commandBus = $container->get(CommandBus::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineUserFactory = $container->get(DoctrineUserFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));
    $this->invalidProductId = new ProductId();
    $this->user = $this->doctrineUserFactory->guestUser()->build(new UserId());
    $this->doctrineUserRepository->save($this->user);
    $this->doctrineProductRepository->save($this->product);
    $this->addProductCommand = new AddProductCommand($this->user->id(), $this->product->id()->__toString(), 2);
    $this->invalidAddProductCommand = new AddProductCommand($this->user->id(), $this->invalidProductId->__toString(), 2);
    
    
  }

  /** @test */
  public function canAddProduct()
  {
    $added = false;
    $this->commandBus->dispatch($this->addProductCommand);

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
    $this->commandBus->dispatch($this->invalidAddProductCommand);

    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());
    $items = $cart->items();

    $this->assertTrue($items->isEmpty());
  }

  /** @test */
  public function notExistingUserCanNotAddProduct()
  {
    $this->expectException(UserDoesNotExistException::class);
    $this->commandBus->dispatch(new AddProductCommand(new UserId(), $this->product->id()->__toString(), 2));
  }

}