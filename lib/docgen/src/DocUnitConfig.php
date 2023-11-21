<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

class DocUnitConfig
{
    /**
     * @param class-string $className
     */
    public function __construct(
        public DocSection $section,
        public string $className,
        public string $singular,
        public string $outPath,
        public DocExampleType $hasExamples,
        public ?string $stripSuffix = null,
    ) {
    }

}
