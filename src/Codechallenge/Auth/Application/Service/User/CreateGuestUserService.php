<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserFactory;
use App\Codechallenge\Auth\Domain\Model\UserRepository;

/**
 * Service for create a new Guest user.
 * Guest users doesn't have name, email, password and address data.
 */
class CreateGuestUserService
{
    /**
     * Constructor.
     *
     * @param UserRepository $userRepository the user repository object
     * @param UserFactory    $userFactory    the user factory object
     */
    public function __construct(
        private UserRepository $userRepository,
        private UserFactory $userFactory)
    {
    }

    /**
     * Create a new guest user.
     *
     * @return User the user
     */
    public function execute(): User
    {
        $user = $this->userFactory->guestUser()
                                  ->build($this->userRepository->nextIdentity());

        $this->userRepository->save($user);

        return $user;
    }
}
