<?php

namespace PhpTui\BDF;

class BdfBoundingBox
{
    public function __construct(public BdfSize $size, public BdfCoord $offset)
    {
    }

}
