<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Domain\Aggregate;

use App\Codechallenge\Shared\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    private array $recordedEvents = [];

    public function record(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }
}
