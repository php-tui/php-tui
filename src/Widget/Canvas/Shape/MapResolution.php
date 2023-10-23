<?php

namespace PhpTui\Tui\Widget\Canvas\Shape;

use PhpTui\Tui\Widget\Canvas\Shape\Data\MapData;

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
