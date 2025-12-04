<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Application\Service\User\CreateTokenService;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateTokenServiceTest extends KernelTestCase
{
  private $userRepository;
  private $createTokenService;
  private $testTokenString;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->userRepository = $container->get(UserRepository::class);
    $this->createTokenService = $container->get(CreateTokenService::class);
    $this->testTokenString = "testTokenString";

  }

  /** @test */
  public function canCreateTokenExistingUser()
  {
    $user = new User(new UserId(), "testNname", "test@email.com", "testPassword", "address");
    $this->userRepository->save($user);

    $apiToken = $this->createTokenService->execute($user->id(), $this->testTokenString);

    $this->assertNotNull($apiToken);
  }

  /** @test */
  public function canNotCreateTokenForNonExistingUser()
  {
    $this->expectException(UserDoesNotExistException::class);
    $apiToken = $this->createTokenService->execute(new UserId(), $this->testTokenString);

    $this->assertNull($apiToken);
  }
}