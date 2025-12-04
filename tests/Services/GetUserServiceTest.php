<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Application\DTO\UserDTO;
use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Application\Service\User\GetUserService;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetUserServiceTest extends KernelTestCase
{
  private $getUserService;
  private $userRepository;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->getUserService = $container->get(GetUserService::class);
    $this->userRepository = $container->get(UserRepository::class);

  }

  /** @test */
  public function canGetExistingUserInformation()
  {
    $user = new User(new UserId(), "testNname", "test@email.com", "testPassword", "address");
    $this->userRepository->save($user);

    $userDTO = $this->getUserService->execute($user->id());

    $this->assertInstanceOf(UserDTO::class, $userDTO);
    $this->assertEquals($userDTO->name, $user->name());
    $this->assertEquals($userDTO->email, $user->email());
    $this->assertEquals($userDTO->address, $user->address());
    $this->assertEquals($userDTO->registered, $user->registered());
  }

  /** @test */
  public function canNotGetUnexistingUserInformation()
  {
    $this->expectException(UserDoesNotExistException::class);
    $userDTO = $this->getUserService->execute(new UserId());

    $this->assertNull($userDTO);
  }
}