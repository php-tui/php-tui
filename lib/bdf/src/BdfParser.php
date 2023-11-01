<?php

namespace PhpTui\BDF;

use Closure;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $tokens = BdfTokenStream::fromString($string);

        $metadata = $this->parseMetadata($tokens);

        return new BdfFont(
            metadata: $metadata,
            properties: new BdfProperties()
        );
    }

    private function parseMetadata(BdfTokenStream $tokens)
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
}
