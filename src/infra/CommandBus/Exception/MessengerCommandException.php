<?php

declare(strict_types=1);

namespace Infra\CommandBus\Exception;

use App\Command;
use RuntimeException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

final class MessengerCommandException extends RuntimeException implements CommandBusException
{
    /**
     * Use an error code based off of the Space Sergeant.
     * @see https://en.wikipedia.org/wiki/Terrahawks#Characters
     */
    public const MESSENGER_COMMAND_CODE = 101;

    public static function create(ExceptionInterface $exception, Command $command): self
    {
        return new self(
            $command,
            sprintf(
                'Exception thrown during messenger command bus - command: %s, error: "%s"',
                get_class($command),
                $exception->getMessage()
            ),
            $exception
        );
    }

    private function __construct(private Command $command, string $message, ExceptionInterface $previous)
    {
        parent::__construct($message, self::MESSENGER_COMMAND_CODE, $previous);
    }

    public function getCommand(): Command
    {
        return $this->command;
    }
}
