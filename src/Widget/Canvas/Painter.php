<?php

namespace DTL\PhpTui\Widget\Canvas;

class Painter
{
    public function __construct(public CanvasContext $context, public Resolution $resolution)
    {
    }

}
