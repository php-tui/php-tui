<?php

declare(strict_types=1);

namespace PhpTui\Term\Painter;

use PhpTui\Term\Action;
use PhpTui\Term\Action\Clear;
use PhpTui\Term\Action\MoveCursor;
use PhpTui\Term\Action\PrintString;
use PhpTui\Term\Action\RequestCursorPosition;
use PhpTui\Term\Action\Reset;
use PhpTui\Term\Action\SetBackgroundColor;
use PhpTui\Term\Action\SetForegroundColor;
use PhpTui\Term\Action\SetModifier;
use PhpTui\Term\Action\SetRgbBackgroundColor;
use PhpTui\Term\Action\SetRgbForegroundColor;
use PhpTui\Term\Colors;
use PhpTui\Term\Painter;
use RuntimeException;

class HtmlCanvasPainter implements Painter
{
    /**
     * @var list<string>
     */
    private array $chars = [];

    /**
     * @var list<array{bg:?Action,fg:?Action}>
     */
    private array $attributes = [];

    private int $cursorX = 0;

    private int $cursorY = 0;

    /**
     * @var int<1,max>
     */
    private readonly int $width;

    private SetBackgroundColor|null|SetRgbBackgroundColor $bgColor = null;

    private null|SetForegroundColor|SetRgbForegroundColor $fgColor = null;

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
            if ($action instanceof SetBackgroundColor) {
                $this->bgColor = $action;

                continue;
            }
            if ($action instanceof SetForegroundColor) {
                $this->fgColor = $action;

                continue;
            }
            if ($action instanceof Clear) {
                continue;
            }
            if ($action instanceof SetModifier) {
                continue;
            }
            if ($action instanceof RequestCursorPosition) {
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

    }

    public function toString(): string
    {
        $x = 0;
        $y = 0;
        $charChunks = array_chunk($this->chars, $this->width);
        $attrChunks = array_chunk($this->attributes, $this->width);
        $scale = 8;

        $width = $this->width * $scale;
        $height = ceil(count($this->chars) / $this->width) * $scale;
        $canvasId = md5(implode('', $this->chars));
        $html = [
            sprintf(
                '<canvas id="%s" width=%d height=%d></canvas>',
                $canvasId,
                $width,
                $height
            )
        ];
        $html[] = '<script>';
        $html[] = sprintf('let canvas%s = document.getElementById("%s");', $canvasId, $canvasId);
        $html[] = sprintf('let ctx%s = canvas%s.getContext("2d");', $canvasId, $canvasId);
        $html[] = sprintf('ctx%s.font = "12px monospace";', $canvasId);
        $html[] = sprintf('ctx%s.fillStyle = "%s";', $canvasId, $this->toHtmlRgb($this->defaultBgColor));
        $html[] = sprintf(
            'ctx%s.fillRect(0,0,%d,%d);',
            $canvasId,
            $width,
            $height,
        );

        foreach ($this->chars as $offset => $char) {
            if ($char === ' ') {
                continue;
            }
            $attr = $this->attributes[$offset];
            $x = $offset % $this->width;
            $y = floor($offset / $this->width);
            $html[] = sprintf('ctx%s.fillStyle = "%s";', $canvasId, $this->toHtmlRgb($attr['bg'] ?? $this->defaultBgColor));
            $html[] = sprintf(
                'ctx%s.fillRect(%d,%d,%d,%d);',
                $canvasId,
                $x * $scale,
                $y * $scale,
                $scale,
                $scale
            );
            $html[] = sprintf('ctx%s.fillStyle = "%s";', $canvasId, $this->toHtmlRgb($attr['fg'] ?? $this->defaultFgColor));

            $html[] = sprintf(
                'ctx%s.fillText("%s",%d,%d);',
                $canvasId,
                addslashes($char),
                $x * $scale,
                $y * $scale + $scale,
            );
        }
        $html[] = '</script>';

        return implode("\n", $html);
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
        if ($action instanceof SetForegroundColor) {
            if ($action->color === Colors::Reset) {
                return $this->toHtmlRgb($this->defaultFgColor);
            }

            return $this->toHtmlColor($action->color);
        }
        if ($action instanceof SetBackgroundColor) {
            if ($action->color === Colors::Reset) {
                return $this->toHtmlRgb($this->defaultBgColor);
            }

            return $this->toHtmlColor($action->color);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to convert action %s to color',
            $action::class
        ));
    }

    private function toHtmlColor(Colors $color): string
    {
        return match ($color) {
            Colors::Reset => '',
            Colors::Black => 'black',
            Colors::Red => 'red',
            Colors::Green => 'green',
            Colors::Yellow => 'yellow',
            Colors::Blue => 'blue',
            Colors::Magenta => 'darkmagenta',
            Colors::Cyan => 'darkcyan',
            Colors::Gray => 'gray',
            Colors::DarkGray => 'darkgray',
            Colors::LightRed => 'pink',
            Colors::LightGreen => 'lightgreen',
            Colors::LightYellow => 'lightyellow',
            Colors::LightBlue => 'lightblue',
            Colors::LightMagenta => 'magenta',
            Colors::LightCyan => 'cyan',
            Colors::White => 'white',
        };
    }
}
