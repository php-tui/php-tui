<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use PhpTui\Tui\Model\Widget\FractionalPosition;

interface Color
{
    public function debugName(): string;

    /**
     * Return a gradiation at the given fractional position.
     *
     * If this is a simple color (AnsiColor or RgbColor) the simple value will
     * be returned regardless of the fraction given.
     */
    public function at(FractionalPosition $position): Color;
}
