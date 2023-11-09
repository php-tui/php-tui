<?php

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Model\Color;

final class FgBgColor
{
    public function __construct(public Color $fg, public Color $bg)
    {
    }

}
