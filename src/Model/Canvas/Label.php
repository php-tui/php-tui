<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Line;

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
