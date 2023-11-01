<?php

namespace PhpTui\BDF;

final class BdfFont
{
    public function __construct(
        public readonly BdfMetadata $metadata,
        public readonly BdfProperties $properties,
    ) {}
}
