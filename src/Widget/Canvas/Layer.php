<?php

namespace DTL\PhpTui\Widget\Canvas;

final class Layer
{
    /**
     * @param FgBgColor[] $colors
     */
    public function __construct(public array $chars, public array $colors)
    {
    }

}
