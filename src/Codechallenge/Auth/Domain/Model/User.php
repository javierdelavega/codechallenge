<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyRegisteredException;

/**
 * User of the application.
 *
 * There are two types of users
 * Guest users owns a cart, can explore product catalog, can add products to cart and can become registered user
 * Registered users: have same functionality of guest users, in addition can confirm the cart to place orders
 */
class User
{
    public const NAME_MIN_LENGTH = 4;
    public const NAME_MAX_LENGTH = 64;
    public const NAME_FORMAT = '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9 _\-]+$/';
    public const PASSWORD_MIN_LENGTH = 8;
    public const PASSWORD_MAX_LENGTH = 64;
    public const ADDRESS_MIN_LENGTH = 8;
    public const ADDRESS_MAX_LENGTH = 64;

    /**
     * Constructor.
     *
     * @param UserId      $userId   the user id
     * @param string|null $name     the user name, null for guest users
     * @param string|null $email    the email of the user, null for guest users
     * @param string      $password the password of the user, empty for guest users
     * @param string|null $address  the post address of the user, null for guest users
     */
    public function __construct(
        private UserId $userId,
        private ?string $name = null,
        private ?string $email = null,
        private ?string $password = null,
        private ?string $address = null
    ) {
    }

    /**
     * Gets the user id.
     *
     * @return UserId the user id
     */
    public function id(): UserId
    {
        return $this->userId;
    }

    /**
     * Gets the user name.
     *
     * @return string|null the user name, null for guest users
     */
    public function name(): string|null
    {
        return $this->name;
    }

    /**
     * Gets the user email.
     *
     * @return string|null the user email, null for guest users
     */
    public function email(): string|null
    {
        return $this->email;
    }

    /**
     * Gets the user password.
     *
     * @return string the user password, empty for guest users
     */
    public function password(): string|null
    {
        return $this->password;
    }

    /**
     * Gets the post address of the user.
     *
     * @return string|null the post address of the user, null for guest users
     */
    public function address(): string|null
    {
        return $this->address;
    }

    /**
     * Sets the name of the user.
     *
     * @param string $name the name
     *
     * @throws \InvalidArgumentException
     */
    public function setName(string $name): void
    {
        $this->assertNameNotEmpty($name);
        $this->assertNameNotTooShort($name);
        $this->assertNameNotTooLong($name);
        $this->assertNameValidFormat($name);
        $this->name = $name;
    }

    /**
     * Sets the email of the user.
     *
     * @param string $email the email
     *
     * @throws \InvalidArgumentException
     */
    public function setEmail(string $email): void
    {
        $this->assertEmailValidFormat($email);
        $this->email = $email;
    }

    /**
     * Sets the password of the user.
     *
     * @param string $password the password
     *
     * @throws \InvalidArgumentException
     */
    public function setPassword(string $password): void
    {
        $this->assertPasswordNotEmpty($password);
        $this->assertPasswordNotTooShort($password);
        $this->assertPasswordNotTooLong($password);
        $this->password = $password;
    }

    /**
     * Sets the post address of the user.
     *
     * @param string $address the post address
     *
     * @throws \InvalidArgumentException
     */
    public function setAddress(string $address): void
    {
        $this->assertAddressNotEmpty($address);
        $this->assertAddressNotTooShort($address);
        $this->assertAddressdNotTooLong($address);
        $this->address = $address;
    }

    /**
     * Returns if the user is registered, otherwise is Guest.
     *
     * @return bool true if the user is registered, false if the user is guest
     */
    public function registered(): bool
    {
        return null != $this->email;
    }

    /**
     * Creates a security access token for this user.
     *
     * @param ApiTokenId an identity for the token
     * @param string $tokenString a string for the token
     */
    public function createToken(ApiTokenId $apiTokenId, string $tokenString): ApiToken
    {
        return new ApiToken($apiTokenId, $this->id(), $tokenString);
    }

    /**
     * Signup this user. Only guest users can signup to become registered user.
     *
     * @param string $name     the user name
     * @param string $email    the user email
     * @param string $password the user password
     * @param string $address  the user post address
     *
     * @throws UserAlreadyRegisteredException if the user is already registered
     */
    public function signUp(string $name, string $email, string $password, string $address): void
    {
        if ($this->registered()) {
            throw new UserAlreadyRegisteredException();
        }

        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setAddress($address);
    }

    /* Field validation functions */
    private function assertNameNotEmpty(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Empty username');
        }
    }

    private function assertNameNotTooShort(string $name): void
    {
        if (strlen($name) < self::NAME_MIN_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Username must be %d characters or more', self::NAME_MIN_LENGTH));
        }
    }

    private function assertNameNotTooLong(string $name): void
    {
        if (strlen($name) > self::NAME_MAX_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Username must be %d characters or less', self::NAME_MAX_LENGTH));
        }
    }

    private function assertNameValidFormat(string $name): void
    {
        if (1 !== preg_match(self::NAME_FORMAT, $name)) {
            throw new \InvalidArgumentException('Invalid username format');
        }
    }

    private function assertEmailValidFormat(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
    }

    private function assertAddressNotEmpty(string $address): void
    {
        if (empty($address)) {
            throw new \InvalidArgumentException('Empty address');
        }
    }

    private function assertAddressNotTooShort(string $password): void
    {
        if (strlen($password) < self::ADDRESS_MIN_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Address must be %d characters or more', self::ADDRESS_MIN_LENGTH));
        }
    }

    private function assertAddressdNotTooLong(string $address): void
    {
        if (strlen($address) > self::ADDRESS_MAX_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Address must be %d characters or less', self::ADDRESS_MAX_LENGTH));
        }
    }

    private function assertPasswordNotEmpty(string $password): void
    {
        if (empty($password)) {
            throw new \InvalidArgumentException('Empty password');
        }
    }

    private function assertPasswordNotTooShort(string $password): void
    {
        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Password must be %d characters or more', self::PASSWORD_MIN_LENGTH));
        }
    }

    private function assertPasswordNotTooLong(string $password): void
    {
        if (strlen($password) > self::PASSWORD_MAX_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Password must be %d characters or less', self::PASSWORD_MAX_LENGTH));
        }
    }
    /* /Field validation functions */
}
