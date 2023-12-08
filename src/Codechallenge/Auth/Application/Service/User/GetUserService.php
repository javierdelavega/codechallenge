<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Application\DTO\UserDTO;
use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;

/**
 * Service for retrieve user information.
 */
class GetUserService
{
    /**
     * Constructor.
     *
     * @param UserRepository $userRepository the user repository object
     */
    public function __construct(
        private UserRepository $userRepository)
    {
    }

    /**
     * Retrieve user and return an UserDTO with the user information.
     *
     * @return UserDTO DTO with the user information
     *
     * @throws UserDoesNotExistException if the email is already registered
     */
    public function execute(UserId $userId): UserDTO
    {
        $user = $this->userRepository->userOfId($userId);

        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        return new UserDTO($user->name(), $user->email(), $user->address(), $user->registered());
    }
}
