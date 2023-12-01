<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas;

use PhpTui\Tui\Position\FloatPosition;
use PhpTui\Tui\Text\Line;

final class Label
{
    public function __construct(public FloatPosition $position, public Line $line)
    {
    }

    public function width(): int
    {
        return $this->line->width();
    }
}
