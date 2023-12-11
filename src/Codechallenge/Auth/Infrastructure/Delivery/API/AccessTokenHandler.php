<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Delivery\API;

use App\Codechallenge\Auth\Domain\Model\ApiTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

/**
 * Handler for manage the access tokens. (Symfony Security)
 * Gets the access token from the Authorization request header,.
 */
class AccessTokenHandler implements AccessTokenHandlerInterface
{
    /**
     * Constructor.
     *
     * @param ApiTokenRepository $repository the access token repository
     */
    public function __construct(private ApiTokenRepository $repository)
    {
    }

    /**
     * Queries the ApiToken repository, if the token is valid return the
     * user information associated to the token.
     *
     * @param string $accessToken the access token retrieved from Authorization request header
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $accessToken = $this->repository->tokenOfToken($accessToken);
        if (null === $accessToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($accessToken->userId()->__toString());
    }
}
