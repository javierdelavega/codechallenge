<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Domain\Event;

/**
 * Interface for defining a minimal Domain Event.
 */
interface DomainEvent
{
    /**
     * Gets the time when the event occured.
     *
     * @return \DateTimeImmutable
     */
    public function occurredOn();
}
