<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserFactory;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Application\Service\Cart\GetCartTotalService;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetCartTotalServiceTest extends KernelTestCase
{
  private $getCartTotalService;
  private $doctrineUserRepository;
  private $doctrineUserFactory;
  private $doctrineCartRepository;
  private $doctrineCartFactory;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->getCartTotalService = $container->get(GetCartTotalService::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->doctrineUserFactory = $container->get(DoctrineUserFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->doctrineCartFactory = $container->get(DoctrineCartFactory::class);

    $this->user = $this->doctrineUserFactory->guestUser()->build(new UserId());
    $this->doctrineUserRepository->save($this->user);

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    
  }

  /** @test */
  public function canGetCartTotal()
  {
    $cartTotal = 100;
    $cart = $this->doctrineCartRepository->cartOfUser($this->user->id());

    $cart->setCartTotal($cartTotal);
    $this->doctrineCartRepository->save($cart);

    $this->assertEquals($cartTotal, $this->getCartTotalService->execute($this->user->id()));
  }

}