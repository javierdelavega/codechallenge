<?php

declare(strict_types=1);

namespace App\Codechallenge\Shared\Domain\Bus\Query;

interface QueryBus
{
    public function dispatch(Query $query): mixed;
}
