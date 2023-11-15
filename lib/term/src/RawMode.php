<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface RawMode
{
    public function enable(): void;

    public function disable(): void;
}
