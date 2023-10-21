<?php

namespace DTL\PhpTerm;

interface TermBackend
{
    /**
     * @param TermCommand $termCommand
     */
    public function draw(array $commands): void;
}
