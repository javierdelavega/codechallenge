<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyExistException;
use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyRegisteredException;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserRequest;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserService;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SignUpServiceTest extends KernelTestCase
{
  private $signUpUserService;
  private $signUpUserRequest;
  private $userRepository;
  private $guestUser;
  private $registeredUser;
  
  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->signUpUserService = $container->get(SignUpUserService::class);
    $this->userRepository = $container->get(UserRepository::class);

    $this->signUpUserRequest = new SignUpUserRequest("Registered", "test@email.com", "testPassword", "testAddress");

    $this->guestUser = new User(new UserId(), "Guest", null, "", null);
    $this->registeredUser = new User(new UserId(), "Registered", "registered@email.com", "testPassword", "testAddress");
  }

  /** @test */
  public function canSignUpGuestUsers()
  {
    $this->userRepository->save($this->guestUser);
    $userDTO = $this->signUpUserService->execute($this->guestUser->id(), $this->signUpUserRequest);

    $this->assertEquals($userDTO->name, $this->signUpUserRequest->name);
    $this->assertEquals($userDTO->email, $this->signUpUserRequest->email);
    $this->assertEquals($userDTO->address, $this->signUpUserRequest->address);
  }

  /** @test */
  public function canNotSignUpRegisteredUsers()
  {
    $this->userRepository->save($this->registeredUser);
    $this->expectException(UserAlreadyRegisteredException::class);
    $userDTO = $this->signUpUserService->execute($this->registeredUser->id(), $this->signUpUserRequest);

    $this->assertNull($userDTO);
  }

  /** @test */
  public function canNotSignUpAlreadyExistingEmail()
  {
    $this->userRepository->save($this->registeredUser);
    $this->userRepository->save($this->guestUser);

    $this->signUpUserRequest = new SignUpUserRequest("Registered", "registered@email.com", 
                                                    "testPassword", "testAddress"); 

    $this->expectException(UserAlreadyExistException::class);
    $userDTO = $this->signUpUserService->execute($this->registeredUser->id(), $this->signUpUserRequest);

    $this->assertNull($userDTO);
  }

}