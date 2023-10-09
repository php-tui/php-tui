<?php

namespace DTL\PhpTui\Model\Viewport;

class Fixed implements Viewport
{
    public function __construct(public readonly Area $area)
    {
    }
}
