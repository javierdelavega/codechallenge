<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Auth\Domain\Model\ApiTokenId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ApiTokenIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticApiTokenId()
  {
    $tokenId = ApiTokenId::create();
    $this->assertInstanceOf(ApiTokenId::class, $tokenId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $tokenId = new ApiTokenId($id);

    $this->assertEquals($tokenId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $token = new ApiTokenId($id);

    $this->assertEquals($token->id(), $id);
  }

}