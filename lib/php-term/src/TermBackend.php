<?php

namespace DTL\PhpTerm;

interface TermBackend
{
    /**
     * @param TermCommand[] $commands
     */
    public function draw(array $commands): void;
}
