<?php

declare(strict_types=1);

namespace PhpTui\Term\RawMode;

use PhpTui\Term\RawMode;

class TestRawMode implements RawMode
{
    private bool $enabled = false;

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
