<?php

declare(strict_types=1);

namespace Infra\QueryBus\Exception;

use App\Query;
use RuntimeException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

/**
 * @author Barney Hanlon <barney.hanlon@cushon.co.uk>
 */
final class MessengerQueryException extends RuntimeException implements QueryBusException
{
    /**
     * Use the code of one of the Zeroid Dix-Huit.
     * @see https://en.wikipedia.org/wiki/Terrahawks#Characters
     */
    public const MESSENGER_QUERY_CODE = 18;

    public static function create(ExceptionInterface $exception, Query $query): self
    {
        return new self(
            $query,
            sprintf(
                'Exception thrown during messenger query bus - query: %s, error: "%s"',
                get_class($query),
                $exception->getMessage()
            ),
            $exception
        );
    }

    private function __construct(private Query $query, string $message, ExceptionInterface $previous)
    {
        parent::__construct($message, self::MESSENGER_QUERY_CODE, $previous);
    }

    public function getQuery(): Query
    {
        return $this->query;
    }
}
