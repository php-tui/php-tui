<?php

namespace DTL\PhpTerm\Term;

use DTL\PhpTerm\TermBackend;
use DTL\PhpTerm\TermWriter;
use DTL\PhpTerm\Writer\BufferWriter;

final class AnsiTerm implements TermBackend
{
    public function __construct(private TermWriter $writer)
    {
    }

    public static function new(TermWriter $writer): self
    {
        return new self($writer);
    }

    /**
     * @return void
     */
    public function draw(array $commands): void
    {
    }
}
