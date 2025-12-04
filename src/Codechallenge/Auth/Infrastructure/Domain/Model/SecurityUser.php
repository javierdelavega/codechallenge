<?php

namespace App\Codechallenge\Auth\Infrastructure\Domain\Model;

use App\Codechallenge\Auth\Domain\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Class for decouple the Domain User model and the framwork security system
 * The SecurityUser implements the interfaces required by the framework security system and
 * represents a User model wich can be created from this object.
 */
class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private $userId;
    private $email;
    private $password;

    /**
     * Constructor.
     *
     * @param User $user the application user
     */
    public function __construct(User $user)
    {
        $this->userId = $user->id();
        $this->email = $user->email();
        $this->password = $user->password();
    }

    /**
     * Get the user email.
     *
     * @return string the user email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->userId->id();
    }

    /**
     * A Uuid identifier that represents this user.
     *
     * @return Uuid the user Uuid id
     */
    public function getUserUuid(): Uuid
    {
        return new Uuid($this->userId->id());
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
}
