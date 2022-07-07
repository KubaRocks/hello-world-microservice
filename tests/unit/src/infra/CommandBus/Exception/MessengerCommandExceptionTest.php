<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\CommandBus\Exception;

use App\Command;
use Infra\CommandBus\Exception\MessengerCommandException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Messenger\Exception\RuntimeException;

final class MessengerCommandExceptionTest extends TestCase
{
    use ProphecyTrait;

    public function testItIncludesExceptionInTheMessageAndAsPrevious(): void
    {
        /** @var Command $command */
        $command = $this->prophesize(Command::class)->reveal();
        $exc = new RuntimeException('Oh no!');

        $messengerCommandException = MessengerCommandException::create($exc, $command);

        $this->assertSame($exc, $messengerCommandException->getPrevious());
        $this->assertStringContainsString($exc->getMessage(), $messengerCommandException->getMessage());
    }

    public function testItIncludesTheCommand(): void
    {
        /** @var Command $command */
        $command = $this->prophesize(Command::class)->reveal();
        $exc = new RuntimeException('But yes!');

        $messengerCommandException = MessengerCommandException::create($exc, $command);

        $this->assertStringContainsString(get_class($command), $messengerCommandException->getMessage());
        $this->assertSame($command, $messengerCommandException->getCommand());
    }

    public function testItIsASpaceSergeant(): void
    {
        /** @var Command $command */
        $command = $this->prophesize(Command::class)->reveal();
        $exc = new RuntimeException('Whoops');
        $messengerCommandException = MessengerCommandException::create($exc, $command);
        $this->assertSame(101, MessengerCommandException::MESSENGER_COMMAND_CODE);
        $this->assertSame(
            MessengerCommandException::MESSENGER_COMMAND_CODE,
            $messengerCommandException->getCode()
        );
    }
}
