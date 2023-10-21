<?php

namespace DTL\PhpTerm\Action;

use DTL\PhpTerm\Action;

class Reset implements Action
{
    public function __toString(): string
    {
        return 'Reset()';
    }
}
