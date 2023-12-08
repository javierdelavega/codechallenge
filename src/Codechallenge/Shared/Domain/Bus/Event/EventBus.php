<?php

namespace App\Codechallenge\Shared\Domain\Bus\Event;

interface EventBus
{
    public function dispatch(Event $event): void;
}
