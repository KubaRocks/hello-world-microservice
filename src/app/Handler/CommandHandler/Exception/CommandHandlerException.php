<?php

declare(strict_types=1);

namespace App\Handler\CommandHandler\Exception;

use App\Command;
use Throwable;

interface CommandHandlerException extends Throwable
{
    public function getCommand(): Command;
}
