<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Service\User;

use App\Codechallenge\Auth\Domain\Model\ApiToken;
use App\Codechallenge\Auth\Domain\Model\UserId;
use Symfony\Component\Uid\Uuid;

/**
 * Service for create a new security access token for an user.
 */
class CreateTokenService extends ApiTokenService
{
    /**
     * Create a new secutity access token for an user.
     *
     * @param UserId $userId the user id
     *
     * @return ApiToken the security access token
     */
    public function execute(UserId $userId): ApiToken
    {
        $user = $this->findUserOrFail($userId);

        $apiToken = $user->createToken(
            $this->apiTokenRepository->nextIdentity(),
            Uuid::v4()->toBase58()
        );

        $this->apiTokenRepository->save($apiToken);

        return $apiToken;
    }
}
