<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\QueryBus\Exception;

use App\Query;
use Infra\QueryBus\Exception\MessengerQueryException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Messenger\Exception\RuntimeException;

class MessengerQueryExceptionTest extends TestCase
{
    use ProphecyTrait;

    public function testItIncludesExceptionInTheMessageAndAsPrevious(): void
    {
        /** @var Query $query */
        $query = $this->prophesize(Query::class)->reveal();
        $exc = new RuntimeException('Oh no!');

        $messengerQueryException = MessengerQueryException::create($exc, $query);

        $this->assertSame($exc, $messengerQueryException->getPrevious());
        $this->assertStringContainsString($exc->getMessage(), $messengerQueryException->getMessage());
    }

    public function testItIncludesTheCommand(): void
    {
        /** @var Query $query */
        $query = $this->prophesize(Query::class)->reveal();
        $exc = new RuntimeException('But yes!');

        $messengerQueryException = MessengerQueryException::create($exc, $query);

        $this->assertStringContainsString(get_class($query), $messengerQueryException->getMessage());
        $this->assertSame($query, $messengerQueryException->getQuery());
    }

    public function testItIsDixHuit(): void
    {
        /** @var Query $query */
        $query = $this->prophesize(Query::class)->reveal();
        $exc = new RuntimeException('Whoops');
        $messengerQueryException = MessengerQueryException::create($exc, $query);
        $this->assertSame(18, MessengerQueryException::MESSENGER_QUERY_CODE);
        $this->assertSame(
            MessengerQueryException::MESSENGER_QUERY_CODE,
            $messengerQueryException->getCode()
        );
    }
}
