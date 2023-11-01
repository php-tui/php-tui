<?php

namespace PhpTui\BDF;

final class BdfFont
{
    /**
     * @param list<BdfGlyph> $glyphs
     */
    public function __construct(
        public readonly BdfMetadata $metadata,
        public readonly BdfProperties $properties,
        public readonly array $glyphs,
    ) {}
}
