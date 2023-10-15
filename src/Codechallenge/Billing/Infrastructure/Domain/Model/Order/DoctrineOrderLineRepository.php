<?php

namespace App\Codechallenge\Billing\Infrastructure\Domain\Model\Order;


use App\Codechallenge\Billing\Domain\Model\Order\OrderLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for manage the lines of the order using Doctrine ORM
 */
class DoctrineOrderLineRepository extends ServiceEntityRepository
{
  private $entityManager;

  /**
   * Constructor
   * 
   * @param ManagerRegistry $registry doctrine manager registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, OrderLine::class);
    $this->entityManager = $this->getEntityManager();
  }

  /**
   * Add a OrderLine and persist in the database
   * 
   * @param OrderLine $item the item
   */
  public function save(OrderLine $orderLine)
  {
    $this->entityManager->persist($orderLine);
    $this->entityManager->flush();
  }

  /**
   * Removes an OrderLine and delete it from the database
   * 
   * @param OrderLine $orderLine
   */
  public function remove(OrderLine $orderLine)
  {
    $this->entityManager->remove($orderLine);
    $this->entityManager->flush();
  }

}