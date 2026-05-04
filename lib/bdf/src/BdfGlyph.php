<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final readonly class BdfGlyph
{
    /**
     * @param array<int,int> $bitmap
     */
    public function __construct(
        public array $bitmap,
        public BdfBoundingBox $boundingBox,
        public ?int $encoding,
        public string $name,
        public BdfCoord $deviceWidth,
        public ?BdfCoord $scalableWidth
    ) {
    }
}
