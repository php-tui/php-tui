<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Widget\Borders;

final class Block
{
    /**
     * @param int-mask-of<Borders::*> $borders
     */
    public function __construct(
        private int $borders
    ) {
    }
    public static function default(): self
    {
        return new self(Borders::NONE);
    }

    public function inner(Area $area): Area
    {
        return $area;
    }

    /**
     * @param int-mask-of<Borders::*> $flag
     */
    public function borders(int $flag): self
    {
        $this->borders = $flag;
        return $this;
    }
}
