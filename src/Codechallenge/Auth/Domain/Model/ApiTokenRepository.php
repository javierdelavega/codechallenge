<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Repository for manage security access tokens for api access.
 */
interface ApiTokenRepository
{
    /**
     * Adds a token.
     */
    public function save(ApiToken $apitoken);

    /**
     * Removes a token.
     */
    public function remove(ApiToken $apiToken);

    /**
     * Retrieves a token of the given id.
     *
     * @param ApiTokenId the id of the token
     *
     * @return ApiToken the ApiToken with requested id
     */
    public function tokenOfId(ApiTokenId $apiTokenId);

    /**
     * Retrieves a token of the given token string.
     *
     * @param string the token string of the token
     *
     * @return ApiToken
     */
    public function tokenOfToken(string $tokenString);

    /**
     * Gets a new unique ApiToken id.
     *
     * @return ApiTokenId
     */
    public function nextIdentity();
}
