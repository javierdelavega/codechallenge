<?php

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Domain\Model\ApiTokenRepository;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\User;

/**
 * Service for management of the API access's security tokens
 */
abstract class ApiTokenService
{
  protected $userRepository;
  protected $apiTokenRepository;

  /**
   * Constructor
   * 
   * @param UserRepository $userRepository the user repository object
   * @param ApiTokenRepository $apiTokenRepository the apitoken repository object
   */
  public function __construct(UserRepository $userRepository, ApiTokenRepository $apiTokenRepository)
  {
    $this->userRepository = $userRepository;
    $this->apiTokenRepository = $apiTokenRepository;
  }

  /**
   * Find an user by user Id
   * 
   * @param UserId $userId the user id
   * @return User the user
   * @throws UserDoesNotExistException
   */
  protected function findUserOrFail($userId) : User
  {
      $user = $this->userRepository->userOfId(new UserId($userId));
      if (null === $user) {
          throw new UserDoesNotExistException();
      }
      return $user;
  }

}