<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Infrastructure\Bus\Query;

use App\Codechallenge\Shared\Domain\Bus\Query\Query;
use App\Codechallenge\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class InMemoryQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    public function dispatch(Query $query): mixed
    {
        $stamp = $this->queryBus
            ->dispatch($query)
            ->last(HandledStamp::class);

        if (null === $stamp) {
            return null;
        }

        return $stamp->getResult();
    }
}
