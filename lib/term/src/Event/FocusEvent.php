<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\Focus;

final class FocusEvent implements Event
{
    private function __construct(public Focus $focus)
    {
    }

    public static function gained(): self
    {
        return new self(Focus::Gained);
    }

    public static function lost(): self
    {
        return new self(Focus::Lost);
    }

    public function __toString(): string
    {
        return sprintf('Focus(%s)', $this->focus->name);
    }
}
