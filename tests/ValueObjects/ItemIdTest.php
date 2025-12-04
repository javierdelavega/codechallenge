<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ItemIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticItemId()
  {
    $itemId = ItemId::create();
    $this->assertInstanceOf(ItemId::class, $itemId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $itemId = new ItemId($id);

    $this->assertEquals($itemId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $itemId = new ItemId($id);

    $this->assertEquals($itemId->id(), $id);
  }

  /** @test */
  public function canCompareWithOtherId()
  {
    $id = Uuid::v4();
    $anotherId = Uuid::v4();

    $itemId = new ItemId($id);
    $equivalentItemId = new ItemId($id);
    $differentItemId = new ItemId($anotherId);

    $this->assertTrue($itemId->equals($equivalentItemId));
    $this->assertFalse($itemId->equals($differentItemId));
  }

}