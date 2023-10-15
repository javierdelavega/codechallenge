<?php

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use App\Codechallenge\Auth\Domain\Model\UserFactory;

/**
 * Service for create a new Guest user.
 * Guest users doesn't have name, email, password and address data
 */
class CreateGuestUserService
{
  private $userRepository;
  private $userFactory;

  /**
   * Constructor
   * 
   * @param UserRepository $userRepository the user repository object
   * @param UserFactory $userFactory the user factory object
   */
  public function __construct(UserRepository $userRepository, UserFactory $userFactory)
  {
    $this->userRepository = $userRepository;
    $this->userFactory = $userFactory;
  }

  /**
   * Create a new guest user
   * 
   * @return User the user
   */
  public function execute() : User
  {
    $user = $this->userFactory->guestUser()
                              ->build($this->userRepository->nextIdentity());
    
    $this->userRepository->save($user);

    return $user;
  }
}