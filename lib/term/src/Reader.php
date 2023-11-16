<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface Reader
{
    public function read(): ?string;
}
