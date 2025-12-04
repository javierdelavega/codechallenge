<?php

namespace App\Tests\Repositories;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineUserRepositoryTest extends KernelTestCase
{
  private $doctrineUserRepository;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->doctrineUserRepository = $container->get(DoctrineUserRepository::class);
    $this->user = new User(new UserId(), "testNname", "test@email.com", "testPassword", "address");
  }

  /** @test */
  public function canPersistUser()
  {
    $this->doctrineUserRepository->save($this->user);

    $persistedUser = $this->doctrineUserRepository->userOfId($this->user->id());

    $this->assertInstanceOf(User::class, $persistedUser);
  }

  /** @test */
  public function canGetUserOfId()
  {
    $this->doctrineUserRepository->save($this->user);

    $persistedUser = $this->doctrineUserRepository->userOfId($this->user->id());

    $this->assertEquals($persistedUser, $this->user);
  }

  /** @test */
  public function canGetUserOfEmail()
  {
    $this->doctrineUserRepository->save($this->user);

    $persistedUser = $this->doctrineUserRepository->userOfEmail($this->user->email());

    $this->assertEquals($persistedUser, $this->user);
  }

  /** @test */
  public function canRemovePersistedUser()
  {
    $this->doctrineUserRepository->save($this->user);
    $persistedUser = $this->doctrineUserRepository->userOfId($this->user->id());
    $this->assertEquals($persistedUser, $this->user);

    $this->doctrineUserRepository->remove($this->user);
    $persistedUser = $this->doctrineUserRepository->userOfId($this->user->id());
    $this->assertNull($persistedUser);
  }

  /** @test */
  public function canGetNewIidentity()
  {
    $id = $this->doctrineUserRepository->nextIdentity();

    $this->assertInstanceOf(UserId::class, $id);
  }
}
