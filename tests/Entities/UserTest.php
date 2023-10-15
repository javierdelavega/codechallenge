<?php

namespace App\Tests\Entities;

use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyRegisteredException;
use App\Codechallenge\Auth\Domain\Model\ApiTokenId;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use TypeError;

class UserTest extends TestCase
{
  /** @test */
  public function guestUserCanRegister()
  {
    $name= "Registered";
    $email = "test@email.com";
    $password = "testPassword";
    $address = "Test Adrress";
    $user = new User(new UserId(), "Guest", null, "", null);
    $this->assertFalse($user->registered());

    $user->signUp($name, $email, $password, $address);
    
    $this->assertTrue($user->registered());
    $this->assertEquals($user->name(), $name);
    $this->assertEquals($user->email(), $email);
    $this->assertEquals($user->password(), $password);
    $this->assertEquals($user->address(), $address);
  }

  /** @test */
  public function registeredUserCanNotRegister()
  {
    $user = new User(new UserId(), "User", "user@email.com", "testPassword", "Test Address");
    $this->expectException(UserAlreadyRegisteredException::class);
    $user->signUp("Registered", "test@email.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithInvalidEmail()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "testemail.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithNullPassword()
  {
    $user = new User(new UserId(), "Guest", null, "", null);    
    $password = null;
    $this->expectException(TypeError::class);
    $user->signUp("Registered", "test@email.com", $password, "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithEmptyPassword()
  {
    $user = new User(new UserId(), "Guest", null, "", null);    
    $password = "";
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "test@email.com", $password, "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithTooShortPassword()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $password = $this->generateString(User::PASSWORD_MIN_LENGTH - 1);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "test@email.com", $password, "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithTooLongPassword()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $password = $this->generateString(User::PASSWORD_MAX_LENGTH + 1);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "test@email.com", $password, "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithNullName()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $name = null;
    $this->expectException(TypeError::class);
    $user->signUp($name, "test@email.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithEmptyName()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $name = "";
    $this->expectException(InvalidArgumentException::class);
    $user->signUp($name, "test@email.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithTooShortName()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $name = $this->generateString(User::NAME_MIN_LENGTH - 1);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp($name, "test@email.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithTooLongName()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $name = $this->generateString(User::NAME_MAX_LENGTH + 1);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp($name, "test@email.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithNotValidCharactersName()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $name = "Registered%&!";
    $this->expectException(InvalidArgumentException::class);
    $user->signUp($name, "test@email.com", "testPassword", "Test Adrress");
  }

  /** @test */
  public function canNotSignUpWithNullAddress()
  {
    $user = new User(new UserId(), "Guest", null, "", null);    
    $address = null;
    $this->expectException(TypeError::class);
    $user->signUp("Registered", "test@email.com", "testPassword", $address);
  }

  /** @test */
  public function canNotSignUpWithEmptyAddress()
  {
    $user = new User(new UserId(), "Guest", null, "", null);    
    $address = "";
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "test@email.com", "testPassword", $address);
  }

  /** @test */
  public function canNotSignUpWithTooShortAddress()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $address = $this->generateString(User::ADDRESS_MIN_LENGTH - 1);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "test@email.com", "testPassword", $address);
  }

  /** @test */
  public function canNotSignUpWithTooLongAddress()
  {
    $user = new User(new UserId(), "Guest", null, "", null);
    $address = $this->generateString(User::ADDRESS_MAX_LENGTH + 1);
    $this->expectException(InvalidArgumentException::class);
    $user->signUp("Registered", "test@email.com", "testPassword", $address);
  }


  /** @test */
  public function tokenCreatedBelongsCurrentUser()
  {
    $user = new User(new UserId(), "User", "user@email.com", "testPassword", "Test Address");
    $token = $user->createToken(new ApiTokenId(), $user->id(), "testTokenStr");
    
    $this->assertTrue(
        $token->userId()->equals($user->id())
    );
  }

  private function generateString(int $length) : string
  {
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= "a";
    }
    return $str;
  }

  
}