<?php

namespace PhpTui\Term;

interface Writer
{
    public function write(string $bytes): void;

    public function flush(): void;
}
