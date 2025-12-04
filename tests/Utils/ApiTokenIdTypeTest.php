<?php

namespace App\Tests\Utils;

use App\Codechallenge\Auth\Infrastructure\Persistence\Doctrine\Types\ApiTokenIdType;
use PHPUnit\Framework\TestCase;

class ApiTokenIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $userIdType = new ApiTokenIdType();
    $this->assertEquals($userIdType->getName(), ApiTokenIdType::TYPE_NAME);
  }

}