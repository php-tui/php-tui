<?php

namespace PhpTui\Tui\Adapter\ImageMagick\Shape;

use Imagick;
use ImagickPixel;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use RuntimeException;

final class ImagePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof ImageShape) {
            return;
        }

        $image = self::loadImage($shape->path);
        $geo = $image->getImageGeometry();

        /** @var ImagickPixel[] $pixels */
        foreach ($image->getPixelIterator() as $y => $pixels) {
            foreach ($pixels as $x => $pixel) {
                $point = $painter->getPoint(
                    FloatPosition::at(
                        $shape->position->x + $x,
                        $shape->position->y + $geo['height'] - intval($y) - 1
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

    public static function loadImage(string $path): Imagick
    {
        $image = new Imagick();
        if (!file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Imagefile "%s" does not exist',
                $path
            ));
        }
        if (false === $image->readImage($path)) {
            throw new RuntimeException(sprintf(
                'Could not read file "%s"',
                $path
            ));
        }
        return $image;
    }
}
