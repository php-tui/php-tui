<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Shape\Data\MapData;

enum MapResolution
{
    case High;
    case Low;

    /**
     * @return list<array{float,float}>
     */
    public function data(): array
    {
        return match ($this) {
            self::High => MapData::high(),
            self::Low => MapData::low(),
        };
    }
}
