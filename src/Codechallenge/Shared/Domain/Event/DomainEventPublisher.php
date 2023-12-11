<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Domain\Event;

/**
 * Singleton class for publishing Domain Events and attach subscribers.
 */
class DomainEventPublisher
{
    private array $subscribers;
    private static $instance;

    /**
     * Gets an instance of the DomainEventPublisher.
     *
     * @return DomainEventPublisher the instance of DomainEventPublisher
     */
    public static function instance(): self
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Coonstructor.
     */
    private function __construct()
    {
        $this->subscribers = [];
    }

    /**
     * Cannot be cloned.
     *
     * @throws \BadMethodCallException
     */
    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    /**
     * Attach a subscriber to the DomainEventPublisher.
     *
     * @param DomainEventSubscriber $aDomainEventSubscriber the subscriber object
     */
    public function subscribe(DomainEventSubscriber $aDomainEventSubscriber): void
    {
        $this->subscribers[] = $aDomainEventSubscriber;
    }

    /**
     * Publishes a DomainEvent, checks if the subscribers listen for the
     * type of Event, if true calls the handle method.
     */
    private function publish(DomainEvent $anEvent): void
    {
        foreach ($this->subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($anEvent)) {
                $aSubscriber->handle($anEvent);
            }
        }
    }

    public function publishAll(array $events): void
    {
        foreach ($events as $event) {
            $this->publish($event);
        }
    }
}
