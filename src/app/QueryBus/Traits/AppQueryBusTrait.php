<?php

declare(strict_types=1);

namespace App\QueryBus\Traits;

use App\Query;
use App\Result;
use Generator;
use Infra\QueryBus;

trait AppQueryBusTrait
{
    private QueryBus $queryBus;

    /**
     * @param Query $query
     * @return mixed
     */
    public function handle(Query $query): mixed
    {
        return $this->queryBus->handle($query);
    }
}
