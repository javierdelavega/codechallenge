<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Infrastructure\Bus\Command;

use App\Codechallenge\Shared\Domain\Bus\Command\Command;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class InMemoryCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandMessageBus){}

    public function dispatch(Command $command): void
    {
        $this->commandMessageBus->dispatch($command);
    }
}