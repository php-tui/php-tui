<?php

declare(strict_types=1);

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;
use PhpTui\Term\Attribute;

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
