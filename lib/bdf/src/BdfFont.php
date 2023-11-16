<?php

declare(strict_types=1);

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
        if (!isset($this->glyphs[$codePoint])) {
            throw new RuntimeException(sprintf('No glyph for codepoint %d', $codePoint));
        }

        return $this->glyphs[$codePoint];
    }

    /**
     * @return list<BdfGlyph>
     */
    public function glyphs(): array
    {
        return array_values($this->glyphs);
    }
}
