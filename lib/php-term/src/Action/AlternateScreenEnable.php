<?php

namespace DTL\PhpTerm\Action;

use DTL\PhpTerm\Action;

class AlternateScreenEnable implements Action
{
    public function __construct(public readonly bool $enable)
    {
    }

    public function __toString(): string
    {
        return sprintf('AlternateScreenEnable(%s)', $this->enable ? 'true':'false');
    }
}
