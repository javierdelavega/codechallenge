<?php

namespace App\Tests\Utils;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\SymfonyPasswordHashing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineApiTokenRepositoryTest extends KernelTestCase
{
  private $symfonyPasswordHasher;
  private $plainPassword;
  private $user;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->symfonyPasswordHasher = $container->get(SymfonyPasswordHashing::class);
    $this->plainPassword = "testPassword";
    $this->user = new User(new UserId(), "testNname", "test@email.com", $this->plainPassword, "address");
    
  }

  /** @test */
  public function canHashAPasswordForUser()
  {
    $hashedPassword = $this->symfonyPasswordHasher->hash($this->user, $this->plainPassword);
    $this->assertIsString($hashedPassword);
  }

  /** @test */
  public function canVerifyAPassword()
  {
    $hashedPassword = $this->symfonyPasswordHasher->hash($this->user, $this->plainPassword);
    $this->user->setPassword($hashedPassword);

    $this->assertTrue($this->symfonyPasswordHasher->verify($this->user, $this->plainPassword));
  }

}