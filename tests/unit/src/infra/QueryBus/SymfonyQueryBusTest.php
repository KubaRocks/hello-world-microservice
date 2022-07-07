<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\QueryBus;

use App\Query;
use App\Result;
use Infra\QueryBus\Exception\MessengerQueryException;
use Infra\QueryBus\SymfonyQueryBus;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyQueryBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItHandlesAQuery(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $envelope = new Envelope(
            $query,
            [new HandledStamp($result, 'foo')]
        );

        $messageBus->dispatch($query)->willReturn($envelope);
        $symfonyQueryBus = new SymfonyQueryBus($messageBus->reveal());
        $this->assertSame($result, $symfonyQueryBus->handle($query));
    }

    public function testItThrowsAMessengerExceptionIfTheMessageBusThrowsAnException(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $runtimeError = new RuntimeException('test');
        $messageBus->dispatch($query)->willThrow($runtimeError);

        $this->expectExceptionObject(MessengerQueryException::create($runtimeError, $query));

        $symfonyQueryBus = new SymfonyQueryBus($messageBus->reveal());
        $symfonyQueryBus->handle($query);
    }
}
