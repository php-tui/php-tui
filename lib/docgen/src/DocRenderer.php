<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

interface DocRenderer
{
    public function render(DocRenderer $renderer, object $object): ?string;
}
