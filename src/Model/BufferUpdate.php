<?php

namespace DTL\PhpTui\Model;

final class BufferUpdate
{
    public function __construct(public Position $position, public string $char)
    {
    }
}
