<?php

namespace DTL\PhpTui\Widget\Canvas;

enum MapResolution
{
    case High;
    case Low;

    /**
     * @return list<array{int,int}>
     */
    public function data(): array
    {
        return match ($this) {
            self::High => MapData::high(),
            self::Low => MapData::low(),
        };
    }
}
