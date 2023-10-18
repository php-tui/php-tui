<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Color;

final class FgBgColor
{
    public function __construct(public Color $fg, public Color $bg)
    {
    }

}
