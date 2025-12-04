<?php

namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Codechallenge\Shared\Domain\Event\DomainEventPublisher;
use BadMethodCallException;

class DomainEventPublisherTest extends TestCase
{
  /** @test */
  public function canNotCloneSingletonClass()
  {
    $this->expectException(BadMethodCallException::class);
    $cloned = DomainEventPublisher::instance()->__clone();

    $this->assertNull($cloned);
  }

}