<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Infrastructure\Bus\Query;

use App\Codechallenge\Shared\Domain\Bus\Query\Query;
use App\Codechallenge\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\Messenger\MessageBusInterface;

class InMemoryQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $queryMessageBus) {}

    public function dispatch(Query $query): mixed
    {
        /** @var HandledStamp|null $stamp */
        $stamp = $this->queryMessageBus
            ->dispatch($query)
            ->last(HandledStamp::class);

        if (null === $stamp) {
            return null;
        }

        return $stamp->getResult();
    }
}