<?php

namespace App\Tests\DTO;

use App\Codechallenge\Auth\Application\DTO\UserDTO;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use PHPUnit\Framework\TestCase;

class UserDTOTest extends TestCase
{
  private $name = "testName";
  private $email = "test@email.com";
  private $password = "testPassword";
  private $address = "testAddress";
  
  /** @test */
  public function returnValidName()
  {
    $user = new User(new UserId(), $this->name, $this->email, $this->password, $this->address);
    $userDTO = new UserDTO($user->name(), $user->email(), $user->address(), $user->registered());

    $this->assertEquals($userDTO->name, $user->name());
  }

  /** @test */
  public function returnValidEmail()
  {
    $user = new User(new UserId(), $this->name, $this->email, $this->password, $this->address);
    $userDTO = new UserDTO($user->name(), $user->email(), $user->address(), $user->registered());

    $this->assertEquals($userDTO->email, $user->email());
  }

  /** @test */
  public function returnValidAddress()
  {
    $user = new User(new UserId(), $this->name, $this->email, $this->password, $this->address);
    $userDTO = new UserDTO($user->name(), $user->email(), $user->address(), $user->registered());

    $this->assertEquals($userDTO->address, $user->address());
  }

  /** @test */
  public function returnValidRegisteredStatus()
  {
    $user = new User(new UserId(), $this->name, $this->email, $this->password, $this->address);
    $userDTO = new UserDTO($user->name(), $user->email(), $user->address(), $user->registered());

    $this->assertEquals($userDTO->registered, $user->registered());
  }
}