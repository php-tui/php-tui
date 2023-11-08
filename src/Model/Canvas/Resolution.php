<?php

namespace PhpTui\Tui\Model\Canvas;

use Stringable;

final class Resolution implements Stringable
{
    public function __construct(public int $width, public int $height)
    {
    }

    public function __toString(): string
    {
        return sprintf('%sx%s', $this->width, $this->height);
    }
}
