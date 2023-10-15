<?php

namespace App\Codechallenge\Billing\Domain\Model\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Order\OrderId;

/**
 * Repository for management of the orders
 */
interface OrderRepository
{
  /**
   * Adds a order
   * 
   * @param Order $order
   */
  public function save(Order $order);

  /**
   * Removes a order
   * 
   * @param Order $order
   */
  public function remove(Order $order);

  /**
   * Retrieves a order of the given id
   * 
   * @param OrderId the id of the order
   * @return Order the Order with requested id
   */
  public function orderOfId(OrderId $orderId);

  /**
   * Retrieves the orders of the given user id
   * 
   * @param UserId the id of the user
   * @return Array
   */
  public function ordersOfUser(UserId $userId);

  /**
   * Gets a new unique Order id
   * 
   * @return OrderId
   */
  public function nextIdentity();
}