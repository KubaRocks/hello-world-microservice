<?php

declare(strict_types=1);

namespace Infra\QueryBus;

use App\Query;
use Infra\QueryBus;
use Infra\QueryBus\Exception\MessengerQueryException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(
        MessageBusInterface $queryBus,
    ) {
        $this->messageBus = $queryBus;
    }

    /**
     * @param Query $query
     * @return mixed
     */
    public function handle(Query $query): mixed
    {
        try {
            return $this->handleQuery($query);
        } catch (ExceptionInterface $exc) {
            throw MessengerQueryException::create($exc, $query);
        }
    }
}
