<?php

declare(strict_types=1);

namespace PhpTui\Term\Painter;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use PhpTui\Term\Action;
use PhpTui\Term\Action\MoveCursor;
use PhpTui\Term\Action\PrintString;
use PhpTui\Term\Action\Reset;
use PhpTui\Term\Action\SetRgbBackgroundColor;
use PhpTui\Term\Action\SetRgbForegroundColor;
use PhpTui\Term\Painter;
use RuntimeException;

class ImageMagickPainter implements Painter
{
    /**
     * @var list<string>
     */
    private array $chars = [];

    /**
     * @var list<array{bg:?SetRgbBackgroundColor,fg:?SetRgbForegroundColor}>
     */
    private array $attributes = [];

    private int $cursorX = 0;

    private int $cursorY = 0;

    /**
     * @var int<1,max>
     */
    private readonly int $width;
    /**
     * @var int<1,max>
     */
    private readonly int $height;

    private ?SetRgbBackgroundColor $bgColor = null;

    private ?SetRgbForegroundColor $fgColor = null;

    private readonly SetRgbBackgroundColor $defaultBgColor;

    private readonly SetRgbForegroundColor $defaultFgColor;

    private function __construct(
        int $width,
        int $height,
    ) {
        if ($width < 1 || $height < 1) {
            throw new RuntimeException(sprintf(
                'Width or height cannot be less than 1, got width: %d, height: %d',
                $width,
                $height
            ));
        }
        $this->chars = array_fill(0, $width * $height, ' ');
        $this->attributes = array_fill(0, $width * $height, [
            'fg' => null,
            'bg' => null,
        ]);
        $this->width = $width;
        $this->height = $height;
        $this->defaultBgColor = new SetRgbBackgroundColor(10, 10, 10);
        $this->defaultFgColor = new SetRgbForegroundColor(255, 255, 255);
    }

    public static function default(int $width, int $height): self
    {
        return new self(
            $width,
            $height,
        );
    }

    public function paint(array $actions): void
    {
        foreach ($actions as $action) {
            if ($action instanceof PrintString) {
                $this->printString($action);
                continue;
            }
            if ($action instanceof MoveCursor) {
                $this->cursorX = $action->col - 1;
                $this->cursorY = $action->line - 1;

                continue;
            }
            if ($action instanceof SetRgbBackgroundColor) {
                $this->bgColor = $action;

                continue;
            }
            if ($action instanceof SetRgbForegroundColor) {
                $this->fgColor = $action;

                continue;
            }
            if ($action instanceof Reset) {
                $this->fgColor = null;
                $this->bgColor = null;

                continue;
            }

            throw new RuntimeException(sprintf(
                'Do not know how to handle action: %s',
                $action::class
            ));

        }
        dd('done');

    }

    public function toImage(): string
    {
        $x = 0;
        $y = 0;
        $charChunks = array_chunk($this->chars, $this->width);
        $attrChunks = array_chunk($this->attributes, $this->width);
        $cellWidth = 10;
        $cellHeight = 20;

        $width = $this->width * $cellWidth;
        $height = $this->height * $cellHeight;

        $image = new Imagick();

        $image->newImage(intval($width), $height, new ImagickPixel('transparent'));
        $x = 0;
        $lastY = null;

        foreach ($this->chars as $index => $char) {
            $attr = $this->attributes[$index];

            $y = floor($index / $this->width);
            if ($lastY !== $y) {
                $x = 0;
            }
            $lastY = $y;

            $bg = $attr['bg'] ?? $this->defaultBgColor;
            $fg = $attr['fg'] ?? $this->defaultFgColor;

            $draw = new ImagickDraw();
            $draw->setFillColor($this->toHtmlRgb($bg));
            $draw->rectangle(
                $x,
                $y * $cellHeight,
                $x + $cellWidth,
                $y * $cellHeight + $cellHeight,
            );
            $draw->setFillColor($this->toHtmlRgb($fg));
            $draw->setStrokeColor($this->toHtmlRgb($fg));
            //dd(Imagick::queryFonts());
            $draw->setFont('DejaVu-Sans-Mono');
            $draw->setFontSize($cellHeight * 0.9);
            $draw->annotation(
                $x,
                $y * $cellHeight + $cellHeight - ($cellHeight / 4),
                $char
            );
            $image->drawImage($draw);
            $x += $cellWidth;
        }

        $image->setImageFormat('png');
        return $image->__toString();
    }

    private function printString(PrintString $action): void
    {
        foreach (mb_str_split($action->string) as $char) {
            $this->paintChar($this->cursorX, $this->cursorY, $char);
            $this->cursorX++;
        }
    }

    private function paintChar(int $x, int $y, string $char): void
    {
        $offset = ($y * $this->width + 1) + $x - 1;
        $this->chars[$offset] = $char;
        $this->attributes[$offset]['bg'] = $this->bgColor;
        $this->attributes[$offset]['fg'] = $this->fgColor;
    }

    private function toHtmlRgb(Action $action): string
    {
        if (
            $action instanceof SetRgbBackgroundColor ||
            $action instanceof SetRgbForegroundColor
        ) {
            return sprintf('rgb(%d,%d,%d)', $action->r, $action->g, $action->b);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to convert action %s to color',
            $action::class
        ));
    }
}
