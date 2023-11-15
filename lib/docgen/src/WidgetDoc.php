<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

final class WidgetDoc
{
    /**
     * @param array<int,WidgetParam> $params
     */
    public function __construct(
        public string $name,
        public string $className,
        public ?string $description,
        public array $params
    ) {
    }
}
