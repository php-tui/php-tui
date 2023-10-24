<?php

namespace PhpTui\Term;

interface Reader
{
    public function read(): ?string;
}
