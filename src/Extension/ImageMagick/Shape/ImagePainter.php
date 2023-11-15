<?php

namespace PhpTui\Tui\Extension\ImageMagick\Shape;

use Imagick;
use ImagickPixel;
use PhpTui\Tui\Extension\ImageMagick\ImageRegistry;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Canvas\Label;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Widget\Line as PhpTuiLine;
use PhpTui\Tui\Extension\Core\Shape\Line;
use RuntimeException;

final class ImagePainter implements ShapePainter
{
    private ImageRegistry $registry;

    public function __construct(ImageRegistry $registry = null)
    {
        $this->registry = $registry ?? new ImageRegistry();
    }

    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof ImageShape) {
            return;
        }

        if (!extension_loaded('imagick')) {
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
            return;
        }

        $image = $this->registry->load(
            $shape->path,
            fn (string $path) => self::loadImage($path)
        );
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
