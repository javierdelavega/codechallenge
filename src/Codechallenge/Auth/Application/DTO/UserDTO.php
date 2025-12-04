<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\DTO;

/**
 * Data Transfer Object for delivery user data from Domain layer to Application layer.
 */
readonly class UserDTO
{
    /**
     * Constructor.
     *
     * @param string $name       the user name
     * @param string $email      the user email
     * @param string $address    the user address
     * @param bool   $registered is the user registered?
     */
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $address,
        public bool $registered
    ) {
    }
}
