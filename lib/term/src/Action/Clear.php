<?php

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;
use PhpTui\Term\ClearType;

final class Clear implements Action
{
    public function __construct(public ClearType $clearType)
    {
    }
    public function __toString(): string
    {
        return sprintf('Clear(%s)', $this->clearType->name);
    }
}
