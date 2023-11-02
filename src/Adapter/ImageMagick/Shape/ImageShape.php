<?php

namespace PhpTui\Tui\Adapter\ImageMagick\Shape;

use Imagick;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;
use RuntimeException;

final class ImageShape implements Shape
{
    private function __construct(
        public readonly Imagick $image
    ) {
    }

    public function draw(Painter $painter): void
    {
        $geo = $this->image->getImageGeometry();
        for ($x = 0; $x < $geo['width']; $x++) {
            for ($y = 0; $y  < $geo['height']; $y++) {
                $point = $painter->getPoint(FloatPosition::at($x, $geo['height'] - $y));
                if (null === $point) {
                    continue 2;
                }
                $rgb = $this->image->getImagePixelColor($x, $y)->getColor();
                $painter->paint($point, RgbColor::fromRgb(
                    $rgb['r'],
                    $rgb['g'],
                    $rgb['b']
                ));
            }
        }

    }

    public static function fromFilename(string $filename): self
    {
        $image = new Imagick();
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf(
                'Imagefile "%s" does not exist',
                $filename
            ));
        }
        if (false === $image->readImage($filename)) {
            throw new RuntimeException(sprintf(
                'Could not read file "%s"',
                $filename
            ));
        }
        return new self($image);
    }
}
