<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Exceptions\CartIsEmptyException;
use App\Codechallenge\Billing\Application\Exceptions\UserNotRegisteredException;
use App\Codechallenge\Billing\Application\Service\Order\CreateOrderFromCartService;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Order\DoctrineOrderRepository;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateOrderFromCartServiceTest extends KernelTestCase
{
  private $createOrderFromCartService;
  private $doctrineUserRepository;
  private $doctrineCartRepository;
  private $doctrineOrderRepository;
  private $doctrineProductRepository;
  private $product;
  private $guestUser;
  private $registeredUser;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();
    $this->createOrderFromCartService = $container->get(CreateOrderFromCartService::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineOrderRepository = $container->get(DoctrineOrderRepository::class);
    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);

    $this->guestUser = new User(new UserId(), null, null, "", null);
    $this->registeredUser = new User(new UserId(), "testName", "test@email.com", "testPassword", "testAddress");

    $this->doctrineUserRepository->save($this->guestUser);
    $this->doctrineUserRepository->save($this->registeredUser);

    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));
    $this->doctrineProductRepository->save($this->product);
    

    $cart = new Cart(new CartId(), $this->guestUser->id());
    $this->doctrineCartRepository->save($cart);
    $cart->addProduct($this->product->id(), 3);
    $this->doctrineCartRepository->save($cart);
    $cart = new Cart(new CartId(), $this->registeredUser->id());
    $this->doctrineCartRepository->save($cart);
    $cart->addProduct($this->product->id(), 3);
    $this->doctrineCartRepository->save($cart);

  }

  /** @test */
  public function canCreateOrderFromCart()
  {
    $this->createOrderFromCartService->execute($this->registeredUser->id());

    $orders = $this->doctrineOrderRepository->ordersOfUser($this->registeredUser->id());

    $this->assertEquals(1, count($orders));
  }

  /** @test */
  public function guestUserCanNotCreateOrders()
  {
    $this->expectException(UserNotRegisteredException::class);
    $this->createOrderFromCartService->execute($this->guestUser->id());

    $orders = $this->doctrineOrderRepository->ordersOfUser($this->guestUser->id());

    $this->assertTrue($orders->isEmpty());
  }

  /** @test */
  public function canNotCreateOrderFromEmptyCart()
  {
    $cart = $this->doctrineCartRepository->cartOfUser($this->registeredUser->id());
    $cart->removeProduct($this->product->id());

    $this->expectException(CartIsEmptyException::class);
    $this->createOrderFromCartService->execute($this->registeredUser->id());

    $orders = $this->doctrineOrderRepository->ordersOfUser($this->registeredUser->id());

    $this->assertTrue($orders->isEmpty());
  }


}