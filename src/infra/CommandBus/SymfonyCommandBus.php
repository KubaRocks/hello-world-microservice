<?php

declare(strict_types=1);

namespace Infra\CommandBus;

use App\Command;
use Infra\CommandBus;
use Infra\CommandBus\Exception\MessengerCommandException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus implements CommandBus
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public function handle(Command $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (ExceptionInterface $exc) {
            throw MessengerCommandException::create($exc, $command);
        }
    }
}
