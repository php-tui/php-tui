<?php

namespace PhpTui\Tui\Adapter\ImageMagick\Shape;

use Imagick;
use PhpTui\Tui\Adapter\ImageMagick\ImageRegistry;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Canvas\Label;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Widget\Line as PhpTuiLine;
use PhpTui\Tui\Shape\Line;
use RuntimeException;

final class ImagePainter implements ShapePainter
{
    private ImageRegistry $registry;

    public function __construct(ImageRegistry $registry = null)
    {
        $this->registry = $registry ?: new ImageRegistry();
    }

    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof ImageShape) {
            return;
        }

        if ($this->missingExtension($shapePainter, $painter)) {
            return;
        }

        $image = $this->registry->load(
            $shape->path,
            fn (string $path) => self::loadImage($path)
        );

        [$width, $height] = array_values($image->getImageGeometry());
        $pixels = $image->exportImagePixels(0, 0, $width, $height, 'RGB', Imagick::PIXEL_CHAR);

        for ($y = 0; $y < $height; $y++) {
            $rowOffset = $y * $width;
            for ($x = 0; $x < $width; $x++) {
                $point = $painter->getPoint(
                    FloatPosition::at(
                        $shape->position->x + $x,
                        $shape->position->y + $height - $y - 1
                    )
                );

                if (null === $point) {
                    continue;
                }

                $rgbIndex = ($rowOffset + $x) * 3;
                $painter->paint($point, RgbColor::fromRgb(
                    $pixels[$rgbIndex],     // R
                    $pixels[$rgbIndex + 1], // G
                    $pixels[$rgbIndex + 2]  // B
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

    private function missingExtension(ShapePainter $shapePainter, Painter $painter): bool
    {
        if (extension_loaded('imagick')) {
            return false;
        }

        $shapePainter->draw(
            $shapePainter,
            $painter,
            Line::fromScalars(
                $painter->context->xBounds->min + 1,
                $painter->context->yBounds->min + 1,
                $painter->context->xBounds->max - 1,
                $painter->context->yBounds->max - 1
            )->color(AnsiColor::White)
        );

        $shapePainter->draw(
            $shapePainter,
            $painter,
            Line::fromScalars(
                $painter->context->xBounds->min + 1,
                $painter->context->yBounds->max - 1,
                $painter->context->xBounds->max - 1,
                $painter->context->yBounds->min + 1,
            )->color(AnsiColor::White)
        );

        $painter->context->labels->add(
            new Label(FloatPosition::at(0, 0), PhpTuiLine::fromString('Imagick extension not loaded!'))
        );

        return true;
    }
}
