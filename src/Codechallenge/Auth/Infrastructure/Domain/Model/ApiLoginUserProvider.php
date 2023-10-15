<?php

namespace App\Codechallenge\Auth\Infrastructure\Domain\Model;

use App\Codechallenge\Auth\Infrastructure\Domain\Model\SecurityUser;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


/**
 * Class for loading users from login request source of the symfony security system
 * The request contains email and password
 */
class ApiLoginUserProvider implements UserProviderInterface
{
    private $userRepository;

    /**
     * Constructor
     * 
     * @param UserRepository $userRepository the repository for retrieve the users
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @see UserProviderInterface::loadUserByIdentifier()
     */
    public function loadUserByIdentifier($identifier) : UserInterface
    {
        return $this->fetchUser($identifier);
    }

    /**
     * @see UserProviderInterface::refreshUser()
     */
    public function refreshUser(UserInterface $user) : UserInterface
    {
        if (!$user instanceof SecurityUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $identifier = $user->getUserIdentifier();
        return $this->fetchUser($identifier);
    }

    /**
     * @see UserProviderInterface::supportsClass()
     */
    public function supportsClass($class) : bool
    {
        return SecurityUser::class === $class;
    }

    /**
     * Retrieves the user from the repository
     * 
     * @param string $email the user email
     * @return SecurityUser the SecurityUser object with the user information
     */
    private function fetchUser($email)
    {
        if (null === ($user = $this->userRepository->userOfEmail($email))) {
            throw new UserNotFoundException(
                sprintf('Username "%s" does not exist.', $email)
            );
        }

        return new SecurityUser($user);
    }
}