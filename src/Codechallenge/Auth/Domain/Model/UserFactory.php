<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Factory for creating users.
 */
interface UserFactory
{
    /**
     * Creates a guest user.
     */
    public function guestUser();

    /**
     * Build the user object.
     *
     * @param UserId $userId the user id
     *
     * @return User created user
     */
    public function build(UserId $userId);
}
