<?php

namespace PhpTui\Docgen;

class DocSection
{
    public function __construct(
        public string $title,
        public string $description,
    ){}
}
