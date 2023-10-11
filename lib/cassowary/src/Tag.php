<?php

namespace DTL\Cassowary;

class Tag
{
    public function __construct(public Symbol $marker, public Symbol $other)
    {
    }
}
