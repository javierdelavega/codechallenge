<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Domain\Model;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for manage users using Doctrine ORM.
 */
class DoctrineUserRepository extends ServiceEntityRepository implements UserRepository
{
    private EntityManager $entityManager;

    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * Adds a user and persist in the database.
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Removes a user and delete from the database.
     */
    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * Retrieves a user of the given id.
     *
     * @param UserId the id of the user
     *
     * @return User|null the User with requested id
     */
    public function userOfId(UserId $userId): User|null
    {
        return $this->entityManager->find('App\Codechallenge\Auth\Domain\Model\User', $userId);
    }

    /**
     * Retrieves a user with the given email.
     *
     * @param string the user email
     */
    public function userOfEmail(string $email): User|null
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Gets a new unique User id.
     */
    public function nextIdentity(): UserId
    {
        return new UserId();
    }
}
