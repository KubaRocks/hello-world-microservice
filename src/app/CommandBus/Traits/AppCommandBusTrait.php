<?php

declare(strict_types=1);

namespace App\CommandBus\Traits;

use App\Command;
use Infra\CommandBus;

trait AppCommandBusTrait
{
    private CommandBus $commandBus;

    private function handle(Command $command): void
    {
        $this->commandBus->handle($command);
    }
}
