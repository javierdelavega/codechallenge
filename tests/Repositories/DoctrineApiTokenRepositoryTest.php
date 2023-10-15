<?php

namespace App\Tests\Repositories;

use App\Codechallenge\Auth\Domain\Model\ApiToken;
use App\Codechallenge\Auth\Domain\Model\ApiTokenId;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineApiTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineApiTokenRepositoryTest extends KernelTestCase
{
  private $doctrineApiTokenRepository;
  private $apiToken;
  private $userId;
  private $tokenString;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->doctrineApiTokenRepository = $container->get(DoctrineApiTokenRepository::class);
    $this->userId = new UserId();
    $this->tokenString = "testTokenString";
    $this->apiToken = new ApiToken(new ApiTokenId(), $this->userId, $this->tokenString);
  }

  /** @test */
  public function canPersistToken()
  {
    $this->doctrineApiTokenRepository->save($this->apiToken);

    $persistedToken = $this->doctrineApiTokenRepository->tokenOfId($this->apiToken->id());

    $this->assertInstanceOf(ApiToken::class, $persistedToken);
  }

  /** @test */
  public function canGetTokenOfId()
  {
    $this->doctrineApiTokenRepository->save($this->apiToken);

    $persistedToken = $this->doctrineApiTokenRepository->tokenOfId($this->apiToken->id());

    $this->assertEquals($persistedToken, $this->apiToken);
  }

  /** @test */
  public function canGetUserOfTokenString()
  {
    $this->doctrineApiTokenRepository->save($this->apiToken);

    $persistedToken = $this->doctrineApiTokenRepository->tokenOfToken($this->apiToken->token());

    $this->assertEquals($persistedToken, $this->apiToken);
  }

  /** @test */
  public function canRemovePersistedToken()
  {
    $this->doctrineApiTokenRepository->save($this->apiToken);
    $persistedToken = $this->doctrineApiTokenRepository->tokenOfId($this->apiToken->id());
    $this->assertEquals($persistedToken, $this->apiToken);

    $this->doctrineApiTokenRepository->remove($this->apiToken);
    $persistedToken = $this->doctrineApiTokenRepository->tokenOfId($this->apiToken->id());
    $this->assertNull($persistedToken);
  }
}
