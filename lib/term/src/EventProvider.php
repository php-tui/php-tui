<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface EventProvider
{
    public function next(): ?Event;
}
