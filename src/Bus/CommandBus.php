<?php

namespace AlexRoden\LibraryApiPhp\Bus;

use InvalidArgumentException;

class CommandBus
{
    /**
     * @var array<class-string, CommandHandler>
     */
    protected array $handlers = [];

    public function register(
        string $command,
        CommandHandler $handler,
    ): void {
        $this->handlers[$command] = $handler;
    }

    public function dispatch(object $command): mixed
    {
        $handler = $this->handlers[$command::class] ?? null;

        if (!$handler) {
            throw new InvalidArgumentException(
                sprintf(
                    'No handler registered for command [%s]',
                    $command::class,
                )
            );
        }

        return $handler->handle($command);
    }
}
