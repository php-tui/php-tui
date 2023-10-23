<?php

namespace PhpTui\Tui\Widget\Canvas;

use PhpTui\Tui\Model\Color;

final class FgBgColor
{
    public function __construct(public Color $fg, public Color $bg)
    {
    }

}
