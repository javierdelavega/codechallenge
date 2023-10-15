<?php

namespace App\Codechallenge\Auth\Domain\Model;

use App\Codechallenge\Auth\Domain\Model\ApiToken;
use App\Codechallenge\Auth\Domain\Model\ApiTokenId;

/**
 * Repository for manage security access tokens for api access
 */
interface ApiTokenRepository
{
  /**
   * Adds a token
   * 
   * @param ApiToken $apiToken
   */
  public function save(ApiToken $apitoken);

  /**
   * Removes a token
   * 
   * @param ApiToken $apitoken
   */
  public function remove(ApiToken $apiToken);

  /**
   * Retrieves a token of the given id
   * 
   * @param ApiTokenId the id of the token
   * @return ApiToken the ApiToken with requested id
   */
  public function tokenOfId(ApiTokenId $apiTokenId);

  /**
   * Retrieves a token of the given token string
   * 
   * @param string the token string of the token
   * @return ApiToken
   */
  public function tokenOfToken(string $tokenString);

  /**
   * Gets a new unique ApiToken id
   * 
   * @return ApiTokenId
   */
  public function nextIdentity();

}