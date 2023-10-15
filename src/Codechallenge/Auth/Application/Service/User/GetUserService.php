<?php

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Application\DTO\UserDTO;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * Service for retrieve user information
 */
class GetUserService
{
  private $userRepository;

  /**
   * Constructor
   * 
   * @param UserRepository $userRepository the user repository object
   */
  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /**
   * Retrieve user and return an UserDTO with the user information
   * 
   * @param UserId $userId
   * @throws UserDoesNotExistException if the email is already registered
   * @return UserDTO DTO with the user information
   */
  public function execute(UserId $userId) : UserDTO
  {
    $user = $this->userRepository->userOfId($userId);

    if (null === $user) {
      throw new UserDoesNotExistException();
    }

    return new UserDTO($user);

  }

}