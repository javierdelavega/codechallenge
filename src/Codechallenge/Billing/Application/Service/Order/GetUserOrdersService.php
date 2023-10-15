<?php

namespace App\Codechallenge\Billing\Application\Service\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Order\OrderRepository;

/**
 * Service to get the orders of an user
 */
class GetUserOrdersService
{
  private $orderRepository;

  /**
   * Constructor
   * 
   * @param OrderRepository $orderRepository the order repository object
   */
  public function __construct(OrderRepository $orderRepository)
  {
    $this->orderRepository = $orderRepository;
  }

  /**
   * Get the orders of the given user
   * 
   * @param UserId $userId the user id
   * @return Array|null the orders of the user
   */
  public function execute(UserId $userId) : Array|null
  {
    $orders = $this->orderRepository->ordersOfUser($userId);
    return $orders;
  }
}