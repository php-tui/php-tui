<?php

namespace PhpTui\BDF;

use RuntimeException;

final class BdfFont
{
    /**
     * @param list<BdfGlyph> $glyphs
     */
    public function __construct(
        public readonly BdfMetadata $metadata,
        public readonly BdfProperties $properties,
        private readonly array $glyphs,
    ) {
    }

    public function codePoint(int $codePoint): BdfGlyph
    {
        return $this->glyphs[$codePoint] ?? throw new RuntimeException('No glyph for codepoint %d', $codePoint);
    }

    /**
     * @return list<BdfGlyph>
     */
    public function glyphs(): array
    {
        return array_values($this->glyphs);
    }
}
