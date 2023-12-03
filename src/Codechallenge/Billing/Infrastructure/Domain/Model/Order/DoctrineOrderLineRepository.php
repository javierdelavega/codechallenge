<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Domain\Model\Order;

use App\Codechallenge\Billing\Domain\Model\Order\OrderLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for manage the lines of the order using Doctrine ORM.
 */
class DoctrineOrderLineRepository extends ServiceEntityRepository
{
    private EntityManager $entityManager;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry doctrine manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderLine::class);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * Add a OrderLine and persist in the database.
     */
    public function save(OrderLine $orderLine): void
    {
        $this->entityManager->persist($orderLine);
        $this->entityManager->flush();
    }

    /**
     * Removes an OrderLine and delete it from the database.
     */
    public function remove(OrderLine $orderLine): void
    {
        $this->entityManager->remove($orderLine);
        $this->entityManager->flush();
    }
}
