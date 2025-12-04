<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Domain\Event;

/**
 * Interface for defining the behavior of a Domain Event Subscriber object.
 */
interface DomainEventSubscriber
{
    public function handle(DomainEvent $aDomainEvent);

    /**
     * @return bool
     */
    public function isSubscribedTo(DomainEvent $aDomainEvent);
}
