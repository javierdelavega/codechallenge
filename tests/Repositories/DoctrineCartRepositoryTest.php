<?php

namespace App\Tests\Repositories;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineCartRepositoryTest extends KernelTestCase
{
  private $doctrineCartRepository;
  private $doctrineCartFactory;
  private $doctrineUserRepository;
  private $user;
  private $cartId;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineCartFactory = $container->get(DoctrineCartFactory::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->user = new User(new UserId(), "testName", "test@email.com", "testPassword", "testAddress");
    $this->doctrineUserRepository->save($this->user);
    $this->cartId = new CartId();
  }

  /** @test */
  public function canPersistCart()
  {
    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build($this->cartId);
    $this->doctrineCartRepository->save($cart);

    $persistedCart = $this->doctrineCartRepository->cartOfId($cart->id());

    $this->assertInstanceOf(Cart::class, $persistedCart);
  }

  /** @test */
  public function canGetCartOfId()
  {
    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build($this->cartId);
    $this->doctrineCartRepository->save($cart);

    $persistedCart = $this->doctrineCartRepository->cartOfId($cart->id());

    $this->assertEquals($persistedCart, $cart);
  }

  /** @test */
  public function canGetCartOfUser()
  {
    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build($this->cartId);
    $this->doctrineCartRepository->save($cart);

    $persistedCart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $this->assertEquals($persistedCart, $cart);
  }

  /** @test */
  public function canRemovePersistedCart()
  {
    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build($this->cartId);
    $this->doctrineCartRepository->save($cart);

    $persistedCart = $this->doctrineCartRepository->cartOfId($cart->id());
    $this->assertEquals($persistedCart, $cart);

    $this->doctrineCartRepository->remove($cart);
    $persistedCart = $this->doctrineCartRepository->cartOfId($cart->id());
    $this->assertNull($persistedCart);
  }

  /** @test */
  public function canGetNewIidentity()
  {
    $id = $this->doctrineCartRepository->nextIdentity();

    $this->assertInstanceOf(CartId::class, $id);
  }
}
