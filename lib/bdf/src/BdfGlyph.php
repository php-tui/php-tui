<?php

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

    public function countRowsBelowBaseline(): int
    {
        $count = count($this->bitmap);
        $offset = abs($this->boundingBox->offset->y);
        $rowsToCheck = $count - $offset;

        $rows = 0;
        for ($i = $count - 1; $i >= $rowsToCheck; $i--) {
            if ($this->bitmap[$i] > 0) {
                $rows++;
            }
        }

        return $rows;
    }
}
