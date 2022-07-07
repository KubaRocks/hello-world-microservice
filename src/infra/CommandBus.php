<?php

declare(strict_types=1);

namespace Infra;

use App\Command;

interface CommandBus
{
    public function handle(Command $command): void;
}
