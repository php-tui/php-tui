<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Model\Position\FloatPosition;
use PhpTui\Tui\Model\Text\Line;

class Label
{
    public function __construct(public FloatPosition $position, public Line $line)
    {
    }

    public function width(): int
    {
        return $this->line->width();
    }
}
