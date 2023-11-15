<?php

declare(strict_types=1);

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;
use PhpTui\Term\Colors;

final class SetForegroundColor implements Action
{
    public function __construct(public readonly Colors $color)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetForegroundColor(%s)', $this->color->name);
    }
}
