<?php

namespace App\Tests\ValueObjects;

use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CartIdTest extends TestCase
{
  /** @test */
  public function canCreateStaticCartId()
  {
    $cartId = CartId::create();
    $this->assertInstanceOf(CartId::class, $cartId);
  }

  /** @test */
  public function returnValidIdString()
  {
    $id = Uuid::v4();
    $cartId = new CartId($id);

    $this->assertEquals($cartId->__toString(), $id->__toString());
  }

  /** @test */
  public function returnValidUuidId()
  {
    $id = Uuid::v4();
    $cartId = new CartId($id);

    $this->assertEquals($cartId->id(), $id);
  }

  /** @test */
  public function canCompareWithOtherId()
  {
    $id = Uuid::v4();
    $anotherId = Uuid::v4();

    $cartId = new CartId($id);
    $equivalentCartId = new CartId($id);
    $differentCartId = new CartId($anotherId);

    $this->assertTrue($cartId->equals($equivalentCartId));
    $this->assertFalse($cartId->equals($differentCartId));
  }

}