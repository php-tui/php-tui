<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

final class DocClass
{
    /**
     * @param array<int,WidgetParam> $params
     */
    public function __construct(
        public string $name,
        public string $humanName,
        public string $singular,
        public string $className,
        public ?string $description,
        public array $params
    ) {
    }
}
