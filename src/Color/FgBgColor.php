<?php

declare(strict_types=1);

namespace PhpTui\Tui\Color;

use PhpTui\Tui\Color\Color;

final class FgBgColor
{
    public function __construct(public Color $fg, public Color $bg)
    {
    }

}
