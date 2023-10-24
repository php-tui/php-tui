<?php

namespace PhpTui\Term;

interface EventProvider
{
    public function next(): ?Event;
}
