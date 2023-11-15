<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

class WidgetParam
{
    public function __construct(
        public string $type,
        public string $name,
        public ?string $description
    ) {
    }
}
