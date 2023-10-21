<?php

namespace DTL\PhpTerm\Action;

use DTL\PhpTerm\Action;
use DTL\PhpTerm\Attribute;

final class SetModifier implements Action
{
    public function __construct(public readonly Attribute $modifier, public bool $enable)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetModifier(%s,%s)', $this->modifier->name, $this->enable ? 'on' : 'off');
    }
}
