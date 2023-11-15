<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final class BdfMetadata
{
    public function __construct(
        public readonly ?float $version,
        public readonly ?string $name,
        public readonly ?int $pixelSize,
        public readonly ?BdfSize $resolution,
        public readonly ?BdfBoundingBox $boundingBox
    ) {
    }
}
