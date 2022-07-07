<?php

declare(strict_types=1);

namespace Infra\QueryBus\Decorator;

use App\Query;
use App\Result;
use Generator;
use Infra\QueryBus;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Throwable;

#[AsDecorator(decorates: QueryBus::class, priority: 0)]
final class PsrLoggingQueryBus implements QueryBus
{
    public const CONTEXT_QUERY = 'App:Query';
    public const CONTEXT_RESULT = 'App:Query:Result';

    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly LoggerInterface $logger,
        private readonly string $logLevel = LogLevel::DEBUG
    ) {
    }

    /**
     * @param Query $query
     * @return mixed
     * @throws Throwable
     */
    public function handle(Query $query): mixed
    {
        $this->logQuery($query);
        /** @psalm-suppress MixedAssignment - */
        $result = $this->queryBus->handle($query);
        $this->logResult($query, $result);

        return $result;
    }

    private function logQuery(Query $query): void
    {
        try {
            $this->logger->log(
                $this->logLevel,
                sprintf('Running query %s', get_class($query)),
                [self::CONTEXT_QUERY]
            );
        } catch (Throwable $e) {
            // we swallow Log exceptions as what can we do with them.
        }
    }

    private function logResult(Query $query, mixed $result): void
    {
        try {
            $this->logger->log(
                $this->logLevel,
                $this->formatLogMessage($result, $query),
                [self::CONTEXT_QUERY, self::CONTEXT_RESULT,]
            );
        } catch (Throwable $e) {
            // we swallow Log exceptions as what can we do with them.
        }
    }

    /**
     * @param mixed $result
     * @param Query $query
     * @return string
     */
    private function formatLogMessage(mixed $result, Query $query): string
    {
        return sprintf(
            'Received Result "%s" for Query %s',
            is_object($result) ? get_class($result) : gettype($result),
            get_class($query)
        );
    }
}
