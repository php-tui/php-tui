<?php

namespace DTL\PhpTui\Model\Viewport;

class Inline implements Viewport
{
    public function __construct(public readonly int $height)
    {
    }
}
