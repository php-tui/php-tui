<?php

namespace DTL\Cassowary;

class Symbol
{
    public function __construct(public int $id, public SymbolType $symbolType)
    {
    }

}
