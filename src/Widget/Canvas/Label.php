<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Widget\FloatPosition;

class Label
{
    public function __construct(public FloatPosition $floatPosition, public string $string)
    {
    }

}
