<?php

namespace App\Tests\Entities;

use App\Codechallenge\Auth\Domain\Model\ApiTokenId;
use App\Codechallenge\Auth\Domain\Model\User;
use App\Codechallenge\Auth\Domain\Model\UserId;
use PHPUnit\Framework\TestCase;

class ApiTokenTest extends TestCase
{
  /** @test */
  public function returnsValidTokenId()
  {
    $tokenId = new ApiTokenId();
    $tokenString = "testTokenString";
    $user = new User(new UserId(), "Guest", null, "", null);
    $apiToken = $user->createToken($tokenId, $tokenString);

    $this->assertEquals($apiToken->id(), $tokenId);
  }

  /** @test */
  public function returnsValidUserId()
  {
    $tokenString = "testTokenString";
    $user = new User(new UserId(), "Guest", null, "", null);
    $apiToken = $user->createToken(new ApiTokenId(), $tokenString);

    $this->assertEquals($user->id(), $apiToken->userId());
  }

  /** @test */
  public function returnsValidTokenString()
  {
    $tokenString = "testTokenString";
    $user = new User(new UserId(), "Guest", null, "", null);
    $apiToken = $user->createToken(new ApiTokenId(), $tokenString);

    $this->assertEquals($apiToken->token(), $tokenString);
  }
}