<?php

namespace App\Codechallenge\Auth\Domain\Model;

use Symfony\Component\Uid\Uuid;

/**
 * Value Object for User id management
 */
class UserId
{
  private $id;

  /**
   * Constructor
   * 
   * @param Uuid|null an Uuid user identity
   */
  public function __construct($id = null)
  {
    $this->id = $id ? :Uuid::v4();
  }

  /**
   * Static method for create an UserId object
   * 
   * @param Uuid|null an Uuid user identity
   * @return UserId new UserId object
   */
  public static function create($anId = null ) : UserId
  {
    return new static($anId);
  }

  /**
   * Get the id string
   * 
   * @return string the id string
   */
  public function __toString() : string
  {
      return $this->id;
  }

  /**
   * Get the id
   * 
   * @return string the id Uuid
   */
  public function id() : string
  {
      return $this->id;
  }

  /**
   * Compare given UserId with this UserId
   * 
   * @param UserId $userId the user id to compare
   * @return bool true if the id are equals, false if different
   */
  public function equals(UserId $userId) : bool
  {
    return $this->id() === $userId->id();
  }

}