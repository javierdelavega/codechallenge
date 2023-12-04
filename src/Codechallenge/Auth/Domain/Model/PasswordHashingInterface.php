<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Hasher for the User password.
 */
interface PasswordHashingInterface
{
    /**
     * Verifies if the given plain password is valid for the given user.
     *
     * @return bool
     */
    public function verify(User $user, string $plainPassword);

    /**
     * Hashes the given plain password for the given user.
     *
     * @return string
     */
    public function hash(User $user, string $plainPassword);
}
