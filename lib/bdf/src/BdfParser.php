<?php

namespace PhpTui\BDF;

final class BdfParser
{
    public function parse(string $bytes): BdfFont
    {
        return new BdfFont(
            metadata: new BdfMetadata(
                version: 0,
                name: '',
                pointSize: 0,
                resolution: new BdfSize(width: 0, height: 0),
                boundingBox: new BdfBoundingBox(
                    size: new BdfSize(width: 0, height: 0),
                    offset: new BdfCoord(x: 0, y: 0)
                )
            )
        );
    }
}
