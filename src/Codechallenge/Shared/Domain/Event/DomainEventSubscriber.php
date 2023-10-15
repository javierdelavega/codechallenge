<?php

namespace App\Codechallenge\Shared\Domain\Event;

/**
 * Interface for defining the behavior of a Domain Event Subscriber object
 */
interface DomainEventSubscriber
{
  /**
  * @param DomainEvent $aDomainEvent
  */
  public function handle(DomainEvent $aDomainEvent);
  
  /**
  * @param DomainEvent $aDomainEvent
  * @return bool
  */
  public function isSubscribedTo(DomainEvent $aDomainEvent);
}