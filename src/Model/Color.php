<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

interface Color
{
    public function debugName(): string;

    /**
     * Return a gradiation at the given fraction (0..1)
     *
     * If this is a simple color (AnsiColor or RgbColor) the simple value will
     * be returned regardless of the fraction given.
     */
    public function at(float $fraction): Color;
}
