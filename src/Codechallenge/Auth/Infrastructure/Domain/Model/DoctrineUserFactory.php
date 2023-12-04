<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Domain\Model;

use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserFactory;
use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * Factory for creating users.
 */
class DoctrineUserFactory implements UserFactory
{
    private string $name;
    private ?string $email;
    private string $password;
    private ?string $address;

    /**
     * Creates a guest user
     * Generates a random name and empty password for the Guest user
     * Symfony security system needs not null the passwords.
     */
    public function guestUser(): self
    {
        $randomNumbers = rand(pow(10, 5 - 1), pow(10, 5) - 1);
        $this->name = 'Guest'.$randomNumbers;
        $this->email = null;
        $this->address = null;
        $this->password = '';

        return $this;
    }

    /**
     * Build the user object.
     *
     * @param UserId $userId the user id
     *
     * @return User created user
     */
    public function build(UserId $userId): User
    {
        return new User($userId, $this->name, $this->email, $this->password, $this->address);
    }
}
