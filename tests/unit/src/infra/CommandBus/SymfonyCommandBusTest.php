<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\CommandBus;

use App\Command;
use Infra\CommandBus\Exception\MessengerCommandException;
use Infra\CommandBus\SymfonyCommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyCommandBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItRunsACommand(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);
        $messageBus->dispatch($command)->willReturn(
            new Envelope($command, [new HandledStamp(null, 'foo')])
        );
        $symfonyCommandBus = new SymfonyCommandBus($messageBus->reveal());
        $symfonyCommandBus->handle($command);

        $messageBus->dispatch($command)->shouldHaveBeenCalledOnce();
    }

    public function testItThrowsAMessengerCommandException(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);
        $internalException = new RuntimeException('error');
        $messageBus->dispatch($command)->willThrow($internalException);
        $this->expectExceptionObject(MessengerCommandException::create($internalException, $command));

        $symfonyCommandBus = new SymfonyCommandBus($messageBus->reveal());
        $symfonyCommandBus->handle($command);
    }
}
