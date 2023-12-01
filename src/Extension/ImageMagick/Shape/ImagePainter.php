<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick\Shape;

use ImagickPixel;
use PhpTui\Tui\Extension\Core\Shape\LineShape;
use PhpTui\Tui\Extension\ImageMagick\ImageRegistry;
use PhpTui\Tui\Canvas\Label;
use PhpTui\Tui\Canvas\Painter;
use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Canvas\ShapePainter;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\RgbColor;
use PhpTui\Tui\Position\FloatPosition;
use PhpTui\Tui\Text\Line as PhpTuiLine;

final class ImagePainter implements ShapePainter
{
    private readonly ImageRegistry $registry;

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
                LineShape::fromScalars(
                    $painter->context->xBounds->min + 1,
                    $painter->context->yBounds->min + 1,
                    $painter->context->xBounds->max - 1,
                    $painter->context->yBounds->max - 1
                )->color(AnsiColor::White)
            );
            $shapePainter->draw(
                $shapePainter,
                $painter,
                LineShape::fromScalars(
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
        );
        $geo = $image->getImageGeometry();

        /** @var ImagickPixel[] $pixels */
        foreach ($image->getPixelIterator() as $y => $pixels) {
            foreach ($pixels as $x => $pixel) {
                $point = $painter->getPoint(
                    FloatPosition::at(
                        $shape->position->x + $x,
                        $shape->position->y + $geo['height'] - (int) $y - 1
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
}
