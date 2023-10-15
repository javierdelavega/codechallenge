<?php

namespace App\Tests\Repositories;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use App\Codechallenge\Billing\Domain\Model\Order\Order;
use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Order\DoctrineOrderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineOrderRepositoryTest extends KernelTestCase
{
  private $doctrineOrderRepository;
  private $doctrineOrderFactory;
  private $doctrineUserRepository;
  private $user;
  private $orderId;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->doctrineOrderRepository = $container->get(DoctrineOrderRepository::class);
    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->user = new User(new UserId(), "testName", "test@email.com", "testPassword", "testAddress");
    $this->doctrineUserRepository->save($this->user);
    $this->orderId = new OrderId();
  }

  /** @test */
  public function canPersistOrder()
  {
    $order = new Order($this->orderId, $this->user->id(), $this->user->address());
    $this->doctrineOrderRepository->save($order);

    $persistedOrder = $this->doctrineOrderRepository->orderOfId($order->id());

    $this->assertInstanceOf(Order::class, $persistedOrder);
  }

  /** @test */
  public function canGetOrderOfId()
  {
    $order = new Order($this->orderId, $this->user->id(), $this->user->address());
    $this->doctrineOrderRepository->save($order);

    $persistedOrder = $this->doctrineOrderRepository->orderOfId($order->id());

    $this->assertEquals($persistedOrder, $order);
  }

  /** @test */
  public function canGetOrdersOfUser()
  {
    $added = false;
    $order = new Order($this->orderId, $this->user->id(), $this->user->address());
    $this->doctrineOrderRepository->save($order);

    $persistedOrders = $this->doctrineOrderRepository->ordersOfUser($this->user->id());
    foreach($persistedOrders as $persistedOrder) {
      if ($persistedOrder->userId()->equals($order->userId())) {
        $added = true;
      }
    }

    $this->assertTrue($added);
    
  }

  /** @test */
  public function canRemovePersistedOrder()
  {
    $order = new Order($this->orderId, $this->user->id(), $this->user->address());
    $this->doctrineOrderRepository->save($order);

    $persistedOrder = $this->doctrineOrderRepository->orderOfId($order->id());
    $this->assertEquals($persistedOrder, $order);

    $this->doctrineOrderRepository->remove($order);
    $persistedOrder = $this->doctrineOrderRepository->orderOfId($order->id());
    $this->assertNull($persistedOrder);
  }

  /** @test */
  public function canGetNewIidentity()
  {
    $id = $this->doctrineOrderRepository->nextIdentity();

    $this->assertInstanceOf(OrderId::class, $id);
  }
}
