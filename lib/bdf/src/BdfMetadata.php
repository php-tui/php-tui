<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final readonly class BdfMetadata
{
    public function __construct(
        public ?float $version,
        public ?string $name,
        public ?int $pixelSize,
        public ?BdfSize $resolution,
        public ?BdfBoundingBox $boundingBox
    ) {
    }
}
