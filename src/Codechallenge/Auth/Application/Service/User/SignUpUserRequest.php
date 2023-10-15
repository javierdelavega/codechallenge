<?php

namespace App\Codechallenge\Auth\Application\Service\User;

/**
 * Request for SignUp a user
 */
class SignUpUserRequest
{
  private $email;
  private $password;
  private $name;
  private $address;

  /**
   * Constructor
   * 
   * @param string $name the user name
   * @param string $email the user email
   * @param string $password the user plain password
   * @param string $address the user post address
   */
  public function __construct($name, $email, $password, $address)
  {
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->address = $address;
  }

  /**
   * Get the user name
   * 
   * @return string the user name
   */
  public function name() : string
  {
    return $this->name;
  }

  /**
   * Get the user email
   * 
   * @return string the user email
   */
  public function email() : string
  {
    return $this->email;
  }

  /**
   * Get the user plain password
   * 
   * @return string the user plain password
   */
  public function password() : string
  {
    return $this->password;
  }

  /**
   * Get the user post address
   * 
   * @return string the user post address
   */
  public function address() : string
  {
    return $this->address;
  }
}