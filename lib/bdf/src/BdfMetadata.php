<?php

namespace PhpTui\BDF;

class BdfMetadata
{
    public function __construct(
        public readonly ?float $version,
        public readonly ?string $name,
        public readonly ?int $pointSize,
        public readonly ?BdfSize $resolution,
        public readonly ?BdfBoundingBox $boundingBox
    ) {
    }

}
