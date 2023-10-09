<?php

namespace DTL\PhpTui\Model;

class BufferUpdate
{
    public function __construct(public Position $position, public string $char)
    {
    }
}
