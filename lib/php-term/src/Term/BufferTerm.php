<?php

namespace DTL\PhpTerm\Term;

use DTL\PhpTerm\TermBackend;
use DTL\PhpTerm\TermCommand;

class BufferTerm implements TermBackend
{
    /**
     * @param TermCommand[] $commands
     */
    private function __construct(private array $commands = [])
    {
    }

    public static function new(): self
    {
        return new self([]);
    }

    public function draw(array $commands): void
    {
        $this->commands = array_merge($this->commands, $commands);
    }
    /**
     * @return TermCommand[]
     */
    public function commands(): array
    {
        return $this->commands;
    }
}
