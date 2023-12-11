<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Domain\Model\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * Repository for management of the orders.
 */
interface OrderRepository
{
    /**
     * Adds a order.
     */
    public function save(Order $order): void;

    /**
     * Removes a order.
     */
    public function remove(Order $order): void;

    /**
     * Retrieves a order of the given id.
     *
     * @param OrderId the id of the order
     *
     * @return Order the Order with requested id
     */
    public function orderOfId(OrderId $orderId): ?Order;

    /**
     * Retrieves the orders of the given user id.
     *
     * @param UserId the id of the user
     */
    public function ordersOfUser(UserId $userId): ?array;

    /**
     * Gets a new unique Order id.
     */
    public function nextIdentity(): OrderId;
}
