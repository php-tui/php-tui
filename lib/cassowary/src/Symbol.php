<?php

namespace DTL\Cassowary;

class Symbol
{
    public function __construct(public int $id, public SymbolType $symbolType)
    {
    }

    public static function invalid(): self
    {
        return new self(0, SymbolType::Invalid);
    }

}
