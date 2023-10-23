<?php

namespace PhpTui\Term;

interface Painter
{
    /**
     * @param Action[] $actions
     */
    public function paint(array $actions): void;
}
