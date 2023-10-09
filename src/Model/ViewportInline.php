<?php

namespace DTL\PhpTui\Model;

class ViewportInline implements Viewport

{
    public function __construct(public readonly int $height)
    {
    }
}
