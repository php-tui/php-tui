<?php

namespace DTL\PhpTerm;

use DTL\PhpTerm\InformationProvider\AggregateInformationProvider;
use DTL\PhpTerm\Painter\AnsiPainter;
use DTL\PhpTerm\Writer\StreamWriter;

class Terminal
{
    /**
     * @var Action[]
     */
    private array $queue = [];

    public function __construct(private Painter $painter, private InformationProvider $infoProvider)
    {
    }

    /**
     * Create a new terminal, if no backend is provided a standard ANSI
     * terminal will be created.
     */
    public static function new(Painter $backend = null): self
    {
        return new self($backend ?: AnsiPainter::new(StreamWriter::stdout()), AggregateInformationProvider::new([
        ]));
    }

    /**
     * Return information represented by the given class.
     *
     * @template T of TerminalInformation
     * @param class-string<T> $classFqn
     * @return T|null
     */
    public function info(string $classFqn): ?object
    {
        $info = $this->infoProvider->for($classFqn);
        return $info;
    }

    public function queue(Action $action): self
    {
        $this->queue[] = $action;
        return $this;
    }

    public function flush(): self
    {
        $this->painter->paint($this->queue);
        $this->queue = [];
        return $this;
    }
}
