<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Catalog\Domain\Model\ProductId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ProductIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticProductId()
  {
    $ProductId = ProductId::create();
    $this->assertInstanceOf(ProductId::class, $ProductId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $ProductId = new ProductId($id);

    $this->assertEquals($ProductId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $ProductId = new ProductId($id);

    $this->assertEquals($ProductId->id(), $id);
  }

  /** @test */
  public function canCompareWithOtherId()
  {
    $id = Uuid::v4();
    $anotherId = Uuid::v4();

    $ProductId = new ProductId($id);
    $equivalentProductId = new ProductId($id);
    $differentProductId = new ProductId($anotherId);

    $this->assertTrue($ProductId->equals($equivalentProductId));
    $this->assertFalse($ProductId->equals($differentProductId));
  }

}