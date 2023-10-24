<?php

namespace PhpTui\Term;

interface RawMode
{
    public function enable(): void;

    public function disable(): void;
}
