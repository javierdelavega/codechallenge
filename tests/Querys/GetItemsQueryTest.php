<?php

namespace App\Tests\Querys;


use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Query\GetItemsQuery;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use App\Codechallenge\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetItemsQueryTest extends KernelTestCase
{
  private $queryBus;
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineCartFactory;
  private $doctrineProductRepository;
  private $product;
  private $anotherProduct;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->queryBus = $container->get(QueryBus::class);
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
    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    
  }

  /** @test */
  public function canGetItems()
  {
    $quantity = 5;
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->addProduct($this->product->id(), $quantity);
    $cart->addProduct($this->anotherProduct->id(), $quantity);
    $this->doctrineCartRepository->save($cart);
    
    $items = $this->queryBus->dispatch(new GetItemsQuery($this->user->id()));

    $this->assertEquals(2, count($items));

  }


}