<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Domain\Model;

use App\Codechallenge\Auth\Domain\Model\PasswordHashingInterface;
use App\Codechallenge\Auth\Domain\Model\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Hasher for the User password using symfony password hashing functionalities.
 */
class SymfonyPasswordHashing implements PasswordHashingInterface
{
    private UserPasswordHasher $symfonyHasher;

    public function __construct(UserPasswordHasherInterface $symfonyHasher)
    {
        $this->symfonyHasher = $symfonyHasher;
    }

    /**
     * Hashes the given plain password for the given user.
     *
     * @param User   $user
     * @param string $plainPassword
     */
    public function hash(User $user, string $plainPassword): string
    {
        return $this->symfonyHasher->hashPassword(new SecurityUser($user), $plainPassword);
    }

    /**
     * Verifies if the given plain password is valid for the given user.
     *
     * @param User   $user
     * @param string $plainPassword
     */
    public function verify(User $user, string $plainPassword): bool
    {
        return $this->symfonyHasher->isPasswordValid(new SecurityUser($user), $plainPassword);
    }
}
