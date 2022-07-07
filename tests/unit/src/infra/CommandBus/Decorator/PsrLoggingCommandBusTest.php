<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\CommandBus\Decorator;

use App\Command;
use Infra\CommandBus;
use Infra\CommandBus\Decorator\PsrLoggingCommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class PsrLoggingCommandBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItLogsTheCommand(): void
    {
        $logLevel = LogLevel::INFO;
        $command = $this->prophesize(Command::class)->reveal();
        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle($command)->shouldBeCalled();

        $logger = $this->prophesize(LoggerInterface::class);

        $logger->log(
            $logLevel,
            Argument::containingString(get_class($command)),
            [PsrLoggingCommandBus::CONTEXT_COMMAND]
        )->shouldBeCalled();

        $decorator = new PsrLoggingCommandBus($commandBus->reveal(), $logger->reveal(), $logLevel);
        $decorator->handle($command);
    }
}
