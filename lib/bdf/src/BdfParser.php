<?php

namespace PhpTui\BDF;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $tokens = BdfTokenStream::fromString($string);

        $metadata = $this->parseMetadata($tokens);
        $properties = $this->parseProperties($tokens);

        return new BdfFont(
            metadata: $metadata,
            properties: $properties,
            glyphs: [],
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
}
