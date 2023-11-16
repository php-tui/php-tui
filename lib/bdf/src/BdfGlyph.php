<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final class BdfGlyph
{
    /**
     * @param array<int,int> $bitmap
     */
    public function __construct(
        public readonly array $bitmap,
        public readonly BdfBoundingBox $boundingBox,
        public readonly ?int $encoding,
        public readonly string $name,
        public readonly BdfCoord $deviceWidth,
        public readonly ?BdfCoord $scalableWidth
    ) {
    }
}
