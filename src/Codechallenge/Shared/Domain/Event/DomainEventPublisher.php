<?php

namespace App\Codechallenge\Shared\Domain\Event;

use BadMethodCallException;

/**
 * Singleton class for publishing Domain Events and attach subscribers
 */
class DomainEventPublisher
{
  private $subscribers;
  private static $instance = null;
  
  /**
   * Gets an instance of the DomainEventPublisher
   * 
   * @return DomainEventPublisher the instance of DomainEventPublisher
   */
  public static function instance()
  {
    if (null === static::$instance) {
      static::$instance = new static();
    }
    return static::$instance;
  }

  /**
   * Coonstructor
   */
  private function __construct()
  {
    $this->subscribers = [];
  }
  
  /**
   * Cannot be cloned
   * 
   * @throws BadMethodCallException
   */
  public function __clone()
  {
    throw new BadMethodCallException('Clone is not supported');
  }
  
  /**
   * Attach a subscriber to the DomainEventPublisher
   * 
   * @param DomainEventSubscriber $aDomainEventSubscriber the subscriber object
   */
  public function subscribe(DomainEventSubscriber $aDomainEventSubscriber)
  {
    $this->subscribers[] = $aDomainEventSubscriber;
  }

  /**
   * Publishes a DomainEvent, checks if the subscribers listen for the 
   * type of Event, if true calls the handle method
   */
  public function publish(DomainEvent $anEvent)
  {
    foreach ($this->subscribers as $aSubscriber) {
      if ($aSubscriber->isSubscribedTo($anEvent)) {
        $aSubscriber->handle($anEvent);
      }
    } 
  }
}