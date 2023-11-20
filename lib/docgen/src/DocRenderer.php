<?php

namespace PhpTui\Docgen;

interface DocRenderer
{
    public function render(DocRenderer $renderer, object $object): ?string;
}
