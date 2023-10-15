<?php

namespace App\Tests\Utils;

use App\Codechallenge\Auth\Infrastructure\Persistence\Doctrine\Types\UserIdType;
use PHPUnit\Framework\TestCase;

class UserIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $userIdType = new UserIdType();
    $this->assertEquals($userIdType->getName(), UserIdType::TYPE_NAME);
  }

}