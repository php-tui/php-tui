<?php

namespace PhpTui\Tui\Model;

use PhpTui\Tui\DisplayBuilder;

interface DisplayExtension
{
    public function build(DisplayBuilder $builder): void;
}
