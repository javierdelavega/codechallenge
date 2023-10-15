<?php

namespace App\Tests\Utils;

use App\Codechallenge\Catalog\Infrastructure\Persistence\Doctrine\Types\ProductIdType;
use PHPUnit\Framework\TestCase;

class ProductIdTypetest extends TestCase
{
  /** @test */
  public function canGetName()
  {
    $orderIdType = new ProductIdType();
    $this->assertEquals($orderIdType->getName(), ProductIdType::TYPE_NAME);
  }

}