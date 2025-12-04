<?php

namespace App\Tests\Entities;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\SecurityUser;
use PHPUnit\Framework\TestCase;


class SecurityUserTest extends TestCase
{

  private $registeredUser;
  private $guestUser;

  protected function setUp() : void{
    $this->registeredUser = new User(new UserId(), "testNname", "test@email.com", "testPassword", "address");
    $this->guestUser = new User(new UserId(), null, null, "", null);
  }

  /** @test */
  public function canCreateSecurityUserFromRegisteredUser()
  {
    $securityUser = new SecurityUser($this->registeredUser);

    $this->assertInstanceOf(SecurityUser::class, $securityUser);
  }

  /** @test */
  public function canCreateSecurityUserFromGuestdUser()
  {
    $securityUser = new SecurityUser($this->guestUser);

    $this->assertInstanceOf(SecurityUser::class, $securityUser);
  }

  /** @test */
  public function canGetUserIdentifier()
  {
    $securityUser = new SecurityUser($this->registeredUser);
    $this->assertEquals($securityUser->getUserIdentifier(), $this->registeredUser->id());

    $securityUser = new SecurityUser($this->guestUser);
    $this->assertEquals($securityUser->getUserIdentifier(), $this->guestUser->id());
  }

  /** @test */
  public function canGetEmail()
  {
    $securityUser = new SecurityUser($this->registeredUser);
    $this->assertEquals($securityUser->getEmail(), $this->registeredUser->email());

    $securityUser = new SecurityUser($this->guestUser);
    $this->assertEquals($securityUser->getUserIdentifier(), $this->guestUser->id());
  }

  /** @test */
  public function canGetPassword()
  {
    $securityUser = new SecurityUser($this->registeredUser);
    $this->assertEquals($securityUser->getPassword(), $this->registeredUser->password());

    $securityUser = new SecurityUser($this->guestUser);
    $this->assertEquals($securityUser->getPassword(), $this->guestUser->password());
  }

  /** @test */
  public function canGetRoles()
  {
    $securityUser = new SecurityUser($this->registeredUser);
    $roles = $securityUser->getRoles();

    $this->assertContains('ROLE_USER', $roles);
  }

  /** @test */
  public function canCalleraseCreadentialsMethod()
  {
    $securityUser = new SecurityUser($this->registeredUser);
    $this->assertNull($securityUser->eraseCredentials());
  }
}