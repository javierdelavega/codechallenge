<?php

namespace App\Codechallenge\Auth\Infrastructure\Domain\Model;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Class for loading users from access token source of the symfony security system.
 */
class AccessTokenUserProvider implements UserProviderInterface
{
    /**
     * Constructor.
     *
     * @param UserRepository $userRepository the repository for retrieve the users
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @see UserProviderInterface::loadUserByIdentifier()
     */
    public function loadUserByIdentifier($identifier): UserInterface
    {
        $userId = new UserId(new Uuid($identifier));

        return $this->fetchUser($userId);
    }

    /**
     * @see UserProviderInterface::refreshUser()
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SecurityUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $userId = new UserId($user->getUserUuid());

        return $this->fetchUser($userId);
    }

    /**
     * @see UserProviderInterface::supportsClass()
     */
    public function supportsClass($class): bool
    {
        return SecurityUser::class === $class;
    }

    /**
     * Retrieves the user from the repository.
     *
     * @param UserId $userId the user id
     *
     * @return SecurityUser the SecurityUser object with the user information
     */
    private function fetchUser(UserId $userId)
    {
        if (null === ($user = $this->userRepository->userOfId($userId))) {
            throw new UserNotFoundException(sprintf('Username "%s" does not exist.', $userId));
        }

        return new SecurityUser($user);
    }
}
