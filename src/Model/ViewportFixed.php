<?php

namespace DTL\PhpTui\Model;

class ViewportFixed implements Viewport
{
    public function __construct(public readonly Area $area)
    {
    }
}
