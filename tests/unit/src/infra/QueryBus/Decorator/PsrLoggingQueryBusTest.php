<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\QueryBus\Decorator;

use App\Query;
use App\Result;
use Infra\QueryBus;
use Infra\QueryBus\Decorator\PsrLoggingQueryBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class PsrLoggingQueryBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItLogsTheQueryAndResult(): void
    {
        $logLevel = LogLevel::INFO;
        $query = $this->prophesize(Query::class)->reveal();
        $result = new class () implements Result {
        };
        $queryBus = $this->prophesize(QueryBus::class);

        $queryBus->handle($query)->willReturn($result);
        $logger = $this->prophesize(LoggerInterface::class);

        $decorator = new PsrLoggingQueryBus($queryBus->reveal(), $logger->reveal(), $logLevel);
        $this->assertSame($result, $decorator->handle($query));

        $logger->log(
            $logLevel,
            Argument::containingString(get_class($query)),
            [PsrLoggingQueryBus::CONTEXT_QUERY]
        )->shouldHaveBeenCalledOnce();

        $logger->log(
            $logLevel,
            Argument::containingString(get_class($result)),
            [
                PsrLoggingQueryBus::CONTEXT_QUERY,
                PsrLoggingQueryBus::CONTEXT_RESULT,
            ]
        )->shouldHaveBeenCalled();
    }

    public function testItSwallowsExceptionsThrownByTheLogger(): void
    {
        $logLevel = LogLevel::ERROR;
        $query = $this->prophesize(Query::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();
        $queryBus = $this->prophesize(QueryBus::class);

        $queryBus->handle($query)->willReturn($result);
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log(
            $logLevel,
            Argument::containingString(get_class($query)),
            [PsrLoggingQueryBus::CONTEXT_QUERY]
        )->willThrow(new InvalidArgumentException('query'));

        $logger->log(
            $logLevel,
            Argument::containingString(get_class($result)),
            [
                PsrLoggingQueryBus::CONTEXT_QUERY,
                PsrLoggingQueryBus::CONTEXT_RESULT,
            ]
        )->willThrow(new InvalidArgumentException('result'));

        $decorator = new PsrLoggingQueryBus($queryBus->reveal(), $logger->reveal(), $logLevel);
        $this->assertSame($result, $decorator->handle($query));
    }
}
