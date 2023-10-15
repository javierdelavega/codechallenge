<?php

namespace App\Codechallenge\Auth\Domain\Model;

use Symfony\Component\Uid\Uuid;
//use Ramsey\Uuid\Uuid;

/**
 * Value Object for ApiToken id management
 */
class ApiTokenId
{
  private $id;

  /**
   * Constructor
   * 
   * @param Uuid|null an Uuid token identity
   */
  public function __construct($id = null)
  {
    $this->id = $id ? :Uuid::v4();
    //$this->id = $id ? :Uuid::uuid4()->toString();

  }

  /**
   * Static method for create an ApiTokenId object
   * 
   * @param Uuid|null an Uuid token identity
   * @return self new ApiTokenId object
   */
  public static function create($anId = null ) : ApiTokenId
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
   * Get the id Uuid
   * 
   * @return Uuid the id Uuid
   */
  public function id() : Uuid
  {
      return $this->id;
  }

}