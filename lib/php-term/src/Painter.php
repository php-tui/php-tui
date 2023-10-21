<?php

namespace DTL\PhpTerm;

interface Painter
{
    /**
     * @param Action[] $actions
     */
    public function paint(array $actions): void;
}
