<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

use Symfony\Component\Uid\Uuid;

/**
 * Value Object for ApiToken id management.
 */
class ApiTokenId
{
    private Uuid $id;

    /**
     * Constructor.
     *
     * @param Uuid|null an Uuid token identity
     */
    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?: Uuid::v4();
    }

    /**
     * Static method for create an ApiTokenId object.
     *
     * @param Uuid|null an Uuid token identity
     *
     * @return self new ApiTokenId object
     */
    public static function create(Uuid $anId = null): ApiTokenId
    {
        return new static($anId);
    }

    /**
     * Get the id string.
     *
     * @return string the id string
     */
    public function __toString(): string
    {
        return $this->id->__toString();
    }

    /**
     * Get the id Uuid.
     *
     * @return string the id string
     */
    public function id(): string
    {
        return $this->id->__toString();
    }
}
