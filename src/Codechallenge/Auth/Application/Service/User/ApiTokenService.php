<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Application\Exceptions\UserDoesNotExistException;
use App\Codechallenge\Auth\Domain\Model\ApiTokenRepository;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;

/**
 * Service for management of the API access's security tokens.
 */
abstract class ApiTokenService
{
    /**
     * Constructor.
     *
     * @param UserRepository     $userRepository     the user repository object
     * @param ApiTokenRepository $apiTokenRepository the apitoken repository object
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected ApiTokenRepository $apiTokenRepository
    ) {
    }

    /**
     * Find an user by user Id.
     *
     * @param UserId $userId the user id
     *
     * @return User the user
     *
     * @throws UserDoesNotExistException
     */
    protected function findUserOrFail(UserId $userId): User
    {
        $user = $this->userRepository->userOfId($userId);
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        return $user;
    }
}
