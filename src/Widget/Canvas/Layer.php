<?php

namespace DTL\PhpTui\Widget\Canvas;

final class Layer
{
    /**
     * @param FgBgColor[] $colors
     * @param string[] $chars
     */
    public function __construct(public array $chars, public array $colors)
    {
    }

}
