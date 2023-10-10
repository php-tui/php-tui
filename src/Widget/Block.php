<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;

final class Block
{
    public static function default(): self
    {
        return new self();
    }

    public function inner(Area $area): Area
    {
        return $area;
    }
}
