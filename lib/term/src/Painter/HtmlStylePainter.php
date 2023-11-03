<?php

namespace PhpTui\Term\Painter;

use PhpTui\Term\Action\MoveCursor;
use PhpTui\Term\Action\PrintString;
use PhpTui\Term\Action\Reset;
use PhpTui\Term\Action\SetRgbBackgroundColor;
use PhpTui\Term\Action\SetRgbForegroundColor;
use PhpTui\Term\Painter;
use RuntimeException;

/**
 * Painter which generates HTML span elements with style attributes
 * to represent a terminal in HTML.
 *
 * This class should be considered unstable.
 */
class HtmlStylePainter implements Painter
{
    /**
     * @var list<string>
     */
    private array $chars = [];

    /**
     * @var list<array<string,string>>
     */
    private array $attributes = [];

    private int $cursorX = 0;

    private int $cursorY = 0;

    /**
     * @var int<1,max>
     */
    private int $width;

    private ?SetRgbBackgroundColor $bgColor = null;

    private ?SetRgbForegroundColor $fgColor = null;

    /**
     * @param array<string,string> $defaultCellAttrs
     */
    private function __construct(
        int $width,
        int $height,
        private array $defaultCellAttrs
    ) {
        if ($width < 1 || $height < 1) {
            throw new RuntimeException(sprintf(
                'Width or height cannot be less than 1, got width: %d, height: %d',
                $width,
                $height
            ));
        }
        $this->chars = array_fill(0, $width * $height, ' ');
        $this->attributes = array_fill(0, $width * $height, []);
        $this->width = $width;
    }

    public static function default(int $width, int $height): self
    {
        return new self(
            $width,
            $height,
            [
                'font-family' => 'monospace',
                'color' => 'black',
                'padding' =>  '0px',
                'font-kerning' => 'none',
                'white-space' => 'pre',
                'display' => 'block',
                'float' => 'left',
                'line-height' => '1em',
                'font-size' => '1em',
            ]
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

    }

    public function toString(): string
    {
        $charChunks = array_chunk($this->chars, $this->width);
        $attrChunks = array_chunk($this->attributes, $this->width);

        $html = implode('<div style="clear: both;"></div>', array_map(function (array $row, array $rowAttrs) {
            return implode('', array_map(function (string $char, array $attrs) {
                $attrs = array_merge($this->defaultCellAttrs, $attrs);
                return sprintf(
                    '<div style="%s">%s</div>',
                    implode(
                        ';',
                        array_map(
                            function (string $key, string $value) {
                                return $key . ':'. $value;
                            },
                            array_keys($attrs),
                            array_values($attrs),
                        )
                    ),
                    $char,
                );
            }, $row, $rowAttrs));
        }, $charChunks, $attrChunks));
        $html .= '<div style="clear: both;"></div>';

        return sprintf('%s', $html);
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
        if ($this->bgColor) {
            $this->attributes[$offset]['background-color'] = sprintf(
                '#%02X%02X%02X',
                $this->bgColor->r,
                $this->bgColor->g,
                $this->bgColor->b,
            );
        }
        if ($this->fgColor) {
            $this->attributes[$offset]['color'] = sprintf(
                '#%02X%02X%02X',
                $this->fgColor->r,
                $this->fgColor->g,
                $this->fgColor->b,
            );
        }
    }
}
