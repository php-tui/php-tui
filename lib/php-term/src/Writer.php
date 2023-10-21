<?php

namespace DTL\PhpTerm;

interface Writer
{
    public function write(string $bytes): void;
}
