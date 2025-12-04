<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Auth\Domain\Model\UserId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UserIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticUserId()
  {
    $userId = UserId::create();
    $this->assertInstanceOf(UserId::class, $userId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $userId = new UserId($id);

    $this->assertEquals($userId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $userId = new UserId($id);

    $this->assertEquals($userId->id(), $id);
  }

}