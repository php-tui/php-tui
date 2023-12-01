<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Color;

use PhpTui\Tui\Model\Color\Color;

final class FgBgColor
{
    public function __construct(public Color $fg, public Color $bg)
    {
    }

}
