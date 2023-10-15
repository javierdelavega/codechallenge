<?php

namespace App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Domain\Model\Cart\Cart;
use App\Codechallenge\Billing\Domain\Model\Cart\CartRepository;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for manage carts using Doctrine ORM
 */
class DoctrineCartRepository extends ServiceEntityRepository implements CartRepository
{
  private $entityManager;

  /**
   * Constructor
   * 
   * @param ManagerRegistry $registry doctrine manager registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Cart::class);
    $this->entityManager = $this->getEntityManager();
  }

  /**
   * Adds a cart and persist in the dabase
   * 
   * @param Cart $cart
   */
  public function save(Cart $cart)
  {
    $this->entityManager->persist($cart);
    $this->entityManager->flush();
  }

  /**
   * Removes a cart and delete from the database
   * 
   * @param Cart $cart
   */
  public function remove(Cart $cart)
  {
    $this->entityManager->remove($cart);
    $this->entityManager->flush();
  }

  /**
   * Retrieves a cart of the given id from the database
   * 
   * @param CartId the id of the cart
   * @return Cart|null the Cart with requested id null if the cart does not exist
   */
  public function cartOfId(CartId $cartId) : Cart|null
  {
    return $this->entityManager->find('App\Codechallenge\Billing\Domain\Model\Cart\Cart', $cartId);
  }

  /**
   * Retrieves a cart of the given user id
   * 
   * @param UserId the id of the user
   * @return Cart|null the cart object null if the user does not have a cart
   */
  public function cartOfUser(UserId $userId) : Cart|null
  {
      return $this->createQueryBuilder('c')
          ->andWhere('c.userId = :userId')
          ->setParameter('userId', $userId->id())
          ->getQuery()
          ->getOneOrNullResult()
      ;
  }

  /**
   * Gets a new unique Cart id
   * 
   * @return CartId
   */
  public function nextIdentity()
  {
    return new CartId();
  }
}