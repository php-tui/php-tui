<?php

namespace DTL\PhpTui\Model;

use Generator;

interface LineComposer
{
    /**
     * @return array{list<StyledGrapheme>, int, Alignment}
     */
    public function nextLine(): Generator;
}
