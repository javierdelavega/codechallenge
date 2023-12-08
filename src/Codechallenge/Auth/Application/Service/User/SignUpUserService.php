<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Application\DTO\UserDTO;
use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyExistException;
use App\Codechallenge\Auth\Domain\Model\PasswordHashingInterface;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Domain\Model\UserRepository;

/**
 * Service for signup an user.
 * Only an existing user can sign up
 * Only guest user can sign up.
 */
class SignUpUserService
{
    /**
     * Constructor.
     *
     * @param UserRepository           $userRepository     the user repository object
     * @param PasswordHashingInterface $userPasswordHasher the password hasher object
     */
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHashingInterface $userPasswordHasher
    ) {
    }

    /**
     * Signup a guest user, assigns name, email, password and address to an existing guest user
     * and returns the registered user information through a DTO.
     *
     * @param SignUpUserRequest $request
     *
     * @return UserDTO the registered user
     *
     * @throws UserAlreadyExistException if the email is already registered
     */
    public function execute(UserId $userId, $request): UserDTO
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $address = $request->address;

        // Check if the email is already registered
        $user = $this->userRepository->userOfEmail($email);
        if ($user) {
            throw new UserAlreadyExistException();
        }

        // Get the existing user
        $user = $this->userRepository->userOfId($userId);

        // Hash the password of the user before storing
        $hashedPassword = $this->userPasswordHasher->hash($user, $password);

        $user->signUp($name, $email, $hashedPassword, $address);
        $this->userRepository->save($user);

        return new UserDTO($user->name(), $user->email(), $user->address(), $user->registered());
    }
}
