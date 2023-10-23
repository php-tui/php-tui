<?php

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;

class Reset implements Action
{
    public function __toString(): string
    {
        return 'Reset()';
    }
}
