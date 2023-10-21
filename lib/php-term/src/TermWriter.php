<?php

namespace DTL\PhpTerm;

interface TermWriter
{
    public function write(string $bytes): void;
}
