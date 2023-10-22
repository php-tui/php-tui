<?php

namespace DTL\PhpTerm;

use DTL\PhpTerm\Painter\AnsiPainter;
use DTL\PhpTerm\Writer\StreamWriter;

class Terminal
{
    /**
     * @var Action[]
     */
    private array $queue = [];

    public function __construct(private Painter $painter, private)
    {
    }

    public static function new(Painter $backend = null): self
    {
        return new self($backend ?: AnsiPainter::new(StreamWriter::stdout()));
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
