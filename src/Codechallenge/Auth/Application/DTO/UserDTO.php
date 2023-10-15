<?php

namespace App\Codechallenge\Auth\Application\DTO;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * Data Transfer Object for delivery user data from Domain layer to Application layer
 */
class UserDTO
{
  private $name;
  private $email;
  private $address;
  private $registered;

  /**
   * Constructor
   * 
   * @param User $user User object
   */
  public function __construct(User $user)
  {
    $this->name = $user->name();
    $this->email = $user->email();
    $this->address = $user->address();
    $this->registered = $user->registered();
  }

  /**
   * Get the user name
   * 
   * @return string the name of the user, null if guest user
   */
  public function name() : string|null
  {
    return $this->name;
  }

  /**
   * Get the user email
   * 
   * @return string the email of the user, null if guest user
   */
  public function email() : string|null
  {
    return $this->email;
  }

  /**
   * Get the post address of the user
   * 
   * @return string the post address of the user, null if guest user
   */
  public function address() : string|null
  {
    return $this->address;
  }

  /**
   * Get the registered status of the user
   * 
   * @return bool true if registered, false if not registered
   */
  public function registered() : bool
  {
    return $this->registered;
  }
}