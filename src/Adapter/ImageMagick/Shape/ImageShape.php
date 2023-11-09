<?php

namespace PhpTui\Tui\Adapter\ImageMagick\Shape;

use Imagick;
use ImagickPixel;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use RuntimeException;

/**
 * Renders an image on the canvas.
 */
final class ImageShape implements Shape
{
    private function __construct(
        /**
         * Imagck to render (use `ImageShape::fromFilename` constructor)
         */
        public readonly Imagick $image,

        /**
         * Position to render at (bottom left)
         */
        public FloatPosition $position,
    ) {
    }

    public function position(FloatPosition $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function draw(Painter $painter): void
    {
        $geo = $this->image->getImageGeometry();

        /** @var ImagickPixel[] $pixels */
        foreach ($this->image->getPixelIterator() as $y => $pixels) {
            foreach ($pixels as $x => $pixel) {
                $point = $painter->getPoint(
                    FloatPosition::at(
                        $this->position->x + $x,
                        $this->position->y + $geo['height'] - intval($y) - 1
                    )
                );
                if (null === $point) {
                    continue;
                }
                $rgb = $pixel->getColor();
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
        return new self($image, new FloatPosition(0, 0));
    }
}
