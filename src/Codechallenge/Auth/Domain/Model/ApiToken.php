<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Security access token for api access
 * The API Token belongs and identifiy one user.
 */
class ApiToken
{
    /**
     * Constructor.
     *
     * @param ApiTokenId $apiTokenId the api token id
     * @param UserId     $userId     the user id
     */
    public function __construct(
        private ApiTokenId $apiTokenId,
        private UserId $userId,
        private string $token
    ) {
    }

    /**
     * Get the token id.
     */
    public function id(): ApiTokenId
    {
        return $this->apiTokenId;
    }

    /**
     * Get the user id.
     *
     * @return UserId the user id
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * Get the token string.
     *
     * @return string the token string
     */
    public function token(): string
    {
        return $this->token;
    }
}
