<?php

declare(strict_types=1);

namespace App\Handler\QueryHandler\Exception;

use App\Query;
use Throwable;

interface QueryHandlerException extends Throwable
{
    public function getQuery(): Query;
}
