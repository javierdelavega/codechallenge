<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Infrastructure\Bus\Command;

use App\Codechallenge\Shared\Domain\Bus\Command\Command;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

class InMemoryCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandMessageBus)
    {
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->commandMessageBus->dispatch($command);
        } catch (NoHandlerForMessageException $e) {
            throw new \InvalidArgumentException(sprintf('The command has not a valid handler: %s', $command::class));
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ? $e->getPrevious() : $e;
        }
    }
}
