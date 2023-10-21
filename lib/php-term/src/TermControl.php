<?php

namespace DTL\PhpTerm;

use DTL\PhpTerm\Backend\AnsiBackend;
use DTL\PhpTerm\Writer\StreamWriter;

class TermControl
{
    /**
     * @var TermCommand[]
     */
    private array $queue = [];

    public function __construct(private TermBackend $term)
    {
    }

    public static function new(TermBackend $backend = null): self
    {
        return new self($backend ?: AnsiBackend::new(StreamWriter::stdout()));
    }

    public function queue(TermCommand $command): self
    {
        $this->queue[] = $command;
        return $this;
    }

    public function flush(): self
    {
        $this->term->draw($this->queue);
        $this->queue = [];
        return $this;
    }

    public function getWidth()
    {
    }
}
