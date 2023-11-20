<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

final class DocClass
{
    /**
     * @param array<int,DocParam> $params
     */
    public function __construct(
        public string $name,
        public string $humanName,
        public string $singular,
        public string $className,
        public ?string $documentation,
        public ?string $summary,
        public array $params,
        public bool $hasExample
    ) {
    }
}
