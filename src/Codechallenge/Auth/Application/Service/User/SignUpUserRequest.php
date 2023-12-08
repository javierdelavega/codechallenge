<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Service\User;

/**
 * Request for SignUp a user.
 */
class SignUpUserRequest
{
    /**
     * Constructor.
     *
     * @param string $name     the user name
     * @param string $email    the user email
     * @param string $password the user plain password
     * @param string $address  the user post address
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $address
    ) {
    }
}
