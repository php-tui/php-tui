<?php

namespace DTL\PhpTui\Widget\Canvas;

class Layer
{
    /**
     * @param FgBgColor[] $colors
     */
    public function __construct(public string $string, public array $colors)
    {
    }

}
