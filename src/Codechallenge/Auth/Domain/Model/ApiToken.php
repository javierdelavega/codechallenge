<?php

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Security access token for api access
 * The API Token belongs and identifiy one user
 */
class ApiToken
{
  private $apiTokenId;
  private $userId;
  private $token;

  /**
   * Constructor
   * 
   * @param ApiTokenId $apiTokenId the api token id
   * @param UserId $userId the user id
   * @param string $tokenString the token string
   */
  public function __construct(ApiTokenId $apiTokenId, UserId $userId, $tokenString)
  {
    $this->apiTokenId = $apiTokenId;
    $this->userId = $userId;
    $this->token = $tokenString;
  }

  /**
   * Get the token id
   * 
   * @return ApiTokenId
   */
  public function id() : ApiTokenId
  {
    return $this->apiTokenId;
  }

  /**
   * Get the user id
   * 
   * @return UserId the user id
   */
  public function userId() : UserId
  {
    return $this->userId;
  }

  /**
   * Get the token string
   * 
   * @return string the token string
   */
  public function token() : string
  {
    return $this->token;
  }
  
}