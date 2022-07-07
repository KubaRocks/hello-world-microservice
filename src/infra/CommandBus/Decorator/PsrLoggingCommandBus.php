<?php

declare(strict_types=1);

namespace Infra\CommandBus\Decorator;

use App\Command;
use Infra\CommandBus;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: CommandBus::class, priority: 0)]
final class PsrLoggingCommandBus implements CommandBus
{
    public const CONTEXT_COMMAND = 'App:Command';

    public function __construct(
        private CommandBus $commandBus,
        private LoggerInterface $logger,
        private string $logLevel = LogLevel::DEBUG
    ) {
    }

    public function handle(Command $command): void
    {
        $this->logCommand($command);
        $this->commandBus->handle($command);
    }

    private function logCommand(Command $command): void
    {
        $this->logger->log(
            $this->logLevel,
            sprintf('Running app command %s', get_class($command)),
            [
                self::CONTEXT_COMMAND,
            ]
        );
    }
}
