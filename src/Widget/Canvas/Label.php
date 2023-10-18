<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Widget\FloatPosition;
use DTL\PhpTui\Model\Widget\Line;

class Label
{
    public function __construct(public FloatPosition $position, public Line $line)
    {
    }

}
