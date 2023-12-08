<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Service\Order;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Order\OrderRepository;

/**
 * Service to get the orders of an user.
 */
class GetUserOrdersService
{
    /**
     * Constructor.
     *
     * @param OrderRepository $orderRepository the order repository object
     */
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    /**
     * Get the orders of the given user.
     *
     * @param UserId $userId the user id
     *
     * @return array|null the orders of the user
     */
    public function execute(UserId $userId): array|null
    {
        $orders = $this->orderRepository->ordersOfUser($userId);

        return $orders;
    }
}
