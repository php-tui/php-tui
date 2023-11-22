<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo;

final class CommandBus
{
    /**
     * @param Command[] $commands
     */
    public function __construct(private array $commands)
    {
    }

    /**
     * @return Command[]
     */
    public function drain(): array
    {
        $commands = $this->commands;
        $this->commands = [];

        return $commands;
    }

    public function dispatch(Command $command): void
    {
        $this->commands[] = $command;
    }
}
