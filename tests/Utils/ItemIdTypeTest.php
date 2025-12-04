<?php

namespace App\Tests\Utils;

use App\Codechallenge\Billing\Infrastructure\Persistence\Cart\Doctrine\Types\ItemIdType;
use PHPUnit\Framework\TestCase;

class ItemIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $itemIdType = new ItemIdType();
    $this->assertEquals($itemIdType->getName(), ItemIdType::TYPE_NAME);
  }

}