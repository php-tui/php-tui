<?php

declare(strict_types=1);

namespace PhpTui\Term\RawMode;

use PhpTui\Term\RawMode;

class NullRawMode implements RawMode
{
    public function enable(): void
    {
    }

    public function disable(): void
    {
    }
}
