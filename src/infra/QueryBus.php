<?php

declare(strict_types=1);

namespace Infra;

use App\Query;

interface QueryBus
{
    /**
     * @param Query $query
     * @return mixed
     */
    public function handle(Query $query): mixed;
}
