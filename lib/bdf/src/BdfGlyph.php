<?php

namespace PhpTui\BDF;

final class BdfGlyph
{
    /**
     * @param array<int,int> $bitmap
     */
    public function __construct(
        public array $bitmap,
        public BdfBoundingBox $boundingBox,
        public ?string $encoding,
        public string $name,
        public BdfCoord $deviceWidth,
        public ?BdfCoord $scalableWidth
    )
    {
    }

}
