<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

class DocSection
{
    public function __construct(
        public string $title,
        public string $description,
    ) {
    }
}
