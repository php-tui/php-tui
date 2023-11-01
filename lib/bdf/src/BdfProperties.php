<?php

namespace PhpTui\BDF;

final class BdfProperties
{
    /**
     * @param array<string,string|int> $properties
     */
    public function __construct(public readonly array $properties = [])
    {
    }

    public function get(BdfProperty $property): ?string
    {
        if (isset($this->properties[$property->name])) {
            return $this->properties[$property->name];
        }

        return null;
    }
}
