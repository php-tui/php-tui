<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface Painter
{
    /**
     * @param Action[] $actions
     */
    public function paint(array $actions): void;
}
