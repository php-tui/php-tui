<?php

namespace PhpTui\BDF;

use RuntimeException;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $tokens = BdfTokenStream::fromString($string);

        $metadata = $this->parseMetadata($tokens);
        $properties = $this->parseProperties($tokens);
        $glyphs = $this->parseGlyphs($tokens);

        return new BdfFont(
            metadata: $metadata,
            properties: $properties,
            glyphs: $glyphs,
        );
    }

    private function parseMetadata(BdfTokenStream $tokens): BdfMetadata
    {
        $version = null;
        $name = null;
        $size = null;
        $pointSize = null;
        $size = null;
        $boundingBox = null;

        if ($tokens->is('STARTFONT')) {
            $tokens->advance();
            $version = (float)$tokens->parseLine();
        }

        if ($tokens->is('FONT')) {
            $tokens->advance();
            $name = trim($tokens->parseLine());
        }
        if ($tokens->is('SIZE')) {
            $tokens->advance();
            $pointSize = $tokens->parseInt();
            $resX = $tokens->parseInt();
            $resY = $tokens->parseInt();
            if ($this->notNull($resX, $resY)) {
                /** @phpstan-ignore-next-line */
                $size = new BdfSize($resX, $resY);
            }
        }

        if ($tokens->is('FONTBOUNDINGBOX')) {
            $tokens->advance();
            $boundWidth = $tokens->parseInt();
            $boundHeight = $tokens->parseInt();
            $boundX = $tokens->parseInt();
            $boundY = $tokens->parseInt();
            if ($this->notNull($boundWidth, $boundHeight, $boundY, $boundX)) {
                $boundingBox = new BdfBoundingBox(
                    /** @phpstan-ignore-next-line */
                    size: new BdfSize($boundWidth, $boundHeight),
                    /** @phpstan-ignore-next-line */
                    offset: new BdfCoord($boundX, $boundY),
                );
            }
        }

        return new BdfMetadata(
            version: $version,
            name: $name,
            pointSize: $pointSize,
            resolution: $size,
            boundingBox: $boundingBox,
        );

    }

    /**
     * Helper method to return true if ANY of the given values
     * are NULL
     */
    private function notNull(mixed ...$values): bool
    {
        foreach ($values as $value) {
            if ($value === null) {
                return false;
            }
        }

        return true;
    }

    private function parseProperties(BdfTokenStream $tokens): BdfProperties
    {
        if (!$tokens->is('STARTPROPERTIES')) {
            return new BdfProperties();
        }

        $tokens->advance();
        $tokens->parseLine();
        $properties = [];
        while ($tokens->current() !== null && $tokens->current() !== 'ENDPROPERTIES') {
            $propertyName = $tokens->current();
            $tokens->advance();
            if ($propertyName === 'COMMENT') {
                $tokens->parseLine();
                continue;
            }
            $values = $this->parseValue($tokens);
            if ($values !== null) {
                $properties[$propertyName] = $values;
            }
        }
        if ($tokens->current() !== 'ENDPROPERTIES') {
            throw new RuntimeException('No ENDPROPERTIES token found after STARTPROPERTIES');
        }
        $tokens->advance();
        $tokens->skipWhitespace();

        return new BdfProperties($properties);
    }

    private function parseValue(BdfTokenStream $tokens): int|string|null
    {
        $tokens->skipWhitespace();

        $value = $tokens->current();
        if (null === $value) {
            return null;
        }

        $tokens->advance();
        $tokens->skipWhitespace();

        $value = trim($value);
        if (is_numeric($value)) {
            return (int)$value;
        }
        if (substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
            return substr($value, 1, -1);
        }

        return $value;

    }

    /**
     * @return array<int,BdfGlyph>
     */
    private function parseGlyphs(BdfTokenStream $tokens): array
    {
        $tokens->skipWhitespace();
        $glyphs = [];
        while ($tokens->current() !== null && $tokens->current() !== 'ENDFONT') {
            $glyph = $this->parseGlyph($tokens);
            if (null === $glyph) {
                // TODO: exception here?
                break;
            }
            $glyphs[(int)$glyph->encoding] = $glyph;
        }


        return $glyphs;
    }

    private function parseGlyph(BdfTokenStream $tokens): ?BdfGlyph
    {
        // ignore this
        if ($tokens->current() === 'CHARS') {
            $tokens->parseLine();
        }
        if (!$tokens->is('STARTCHAR')) {
            return null;
        }
        $tokens->advance();
        $tokens->skipWhitespace();

        $name = $tokens->parseLine();

        if (!$tokens->is('ENCODING')) {
            return null;
        }
        $tokens->advance();
        $tokens->skipWhitespace();
        $encoding = $tokens->parseInt();

        $sWidth = null;
        if ($tokens->is('SWIDTH')) {
            $tokens->advance();
            $tokens->skipWhitespace();
            $sWidthX = $tokens->parseInt();
            $sWidthY = $tokens->parseInt();
            if (false === $this->notNull($sWidthX, $sWidthY)) {
                return null;
            }
            /** @phpstan-ignore-next-line */
            $sWidth = new BdfCoord($sWidthX, $sWidthY);
        }

        if (!$tokens->is('DWIDTH')) {
            return null;
        }
        $tokens->advance();
        $tokens->skipWhitespace();
        $dWidthX = $tokens->parseInt();
        $dWidthY = $tokens->parseInt();
        if (false === $this->notNull($dWidthX, $dWidthY)) {
            return null;
        }
        /** @phpstan-ignore-next-line */
        $dWidth = new BdfCoord($dWidthX, $dWidthY);

        if (!$tokens->is('BBX')) {
            return null;
        }
        $tokens->advance();
        $tokens->skipWhitespace();
        $bbxWidth = $tokens->parseInt();
        $bbxHeight = $tokens->parseInt();
        $bbxX = $tokens->parseInt();
        $bbxY = $tokens->parseInt();
        if (false === $this->notNull($bbxX, $bbxY, $bbxX, $bbxY)) {
            return null;
        }
        /** @phpstan-ignore-next-line */
        $bbx = BdfBoundingBox::fromScalars($bbxWidth, $bbxHeight, $bbxX, $bbxY);

        if (!$tokens->is('BITMAP')) {
            return null;
        }
        $tokens->parseLine();
        $bitmap = [];
        while ($tokens->current() !== null && strlen($tokens->current()) === 2) {
            $dec = (int)hexdec($tokens->parseLine());
            $bitmap[] = $dec;
        }

        if ($tokens->current() !== 'ENDCHAR') {
            return null;
        }
        $tokens->parseLine();

        return new BdfGlyph(
            bitmap: $bitmap,
            boundingBox: $bbx,
            encoding: $encoding,
            name: $name,
            deviceWidth: $dWidth,
            scalableWidth: $sWidth
        );
    }
}
