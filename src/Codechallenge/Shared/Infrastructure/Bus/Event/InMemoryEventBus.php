<?php

declare(strict_types=1);

namespace App\MyBusiness\Messenger\Infrastructure;

use App\Codechallenge\Shared\Domain\Bus\Event\Event;
use App\Codechallenge\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

class InMemoryEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function dispatch(Event $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
