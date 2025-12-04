<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Repository for manage users.
 */
interface UserRepository
{
    /**
     * Adds a user.
     */
    public function save(User $user);

    /**
     * Removes a user.
     */
    public function remove(User $user);

    /**
     * Retrieves a user of the given id.
     *
     * @param UserId the id of the user
     *
     * @return User the User with requested id
     */
    public function userOfId(UserId $userId);

    /**
     * Retrieves a user with the given email.
     *
     * @param string the user email
     *
     * @return User
     */
    public function userOfEmail(string $email);

    /**
     * Gets a new unique User id.
     *
     * @return UserId
     */
    public function nextIdentity();
}
