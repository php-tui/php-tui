<?php

declare(strict_types=1);

namespace PhpTui\Tui\Color;

use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\FractionalPosition;

interface Color
{
    public function debugName(): string;

    /**
     * Return a color at the given fractional position.
     *
     * If this is a simple color (AnsiColor or RgbColor) the simple value will
     * be returned regardless of the fraction given.
     */
    public function at(FractionalPosition $position): Color;
}
