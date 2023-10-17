<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\LineComposer;
use DTL\PhpTui\Model\LineComposer\LineTruncator;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Model\Widget\Line;
use DTL\PhpTui\Model\Widget\Span;
use DTL\PhpTui\Model\Widget\StyledGrapheme;
use DTL\PhpTui\Model\Widget\Text;
use DTL\PhpTui\Widget\Paragraph\Wrap;

class Paragraph implements Widget
{
    /** @param array{int,int} $scroll */
    private function __construct(
        private ?Block $block,
        private Style $style,
        private ?Wrap $wrap,
        private Text $text,
        private array $scroll,
        private HorizontalAlignment $alignment
    ) {
    }

    public static function new(Text $text): self
    {
        return new self(
            block: null,
            style: Style::default(),
            wrap: null,
            text: $text,
            scroll: [0,0],
            alignment: HorizontalAlignment::Left,
        );
    }

    public function render(Area $area, Buffer $buffer): void
    {
        $buffer->setStyle($area, $this->style);
        $textArea = $this->resolveTextArea($area, $buffer);

        if ($textArea->height < 1) {
            return;
        }

        $this->text->patchStyle($this->style);
        $style = $this->style;
        $styled = array_map(function (Line $line) use ($style) {
            $graphemes = array_reduce($line->spans, function (array $ac, Span $span) use ($style) {
                foreach ($span->toStyledGraphemes($style) as $grapheme) {
                    $ac[] = $grapheme;
                }
                return $ac;
            }, []);
            return [ $graphemes, $line->alignment ?: $this->alignment ];
        }, $this->text->lines);

        $lineComposer = $this->createLineComposer(
            $styled,
            $textArea,
            $this->wrap,
            $this->scroll[1]
        );

        $y = 0;
        foreach ($lineComposer->nextLine() as $line) {
            [$currentLine, $currentLineWidth, $currentLineAlignment] = $line;

            if ($y >= $this->scroll[0]) {

                $x = $this->getLineOffset($currentLineWidth, $textArea->width, $currentLineAlignment);

                foreach ($currentLine as $grapheme) {
                    if ($grapheme->symbolWidth() === 0) {
                        continue;
                    }
                    $cell = $buffer->get(Position::at(
                        $textArea->left() + $x,
                        $textArea->top() + $y - $this->scroll[0]
                    ));
                    $cell->setChar($grapheme->symbol === '' ? ' ' : $grapheme->symbol);
                    $cell->setStyle($grapheme->style);
                    $x += $grapheme->symbolWidth();
                }
            }

            $y += 1;
            if ($y >= $textArea->height + $this->scroll[0]) {
                break;
            }
        }
    }

    public function style(Style $style): self
    {
        $this->style=  $style;
        return $this;
    }

    public function alignment(HorizontalAlignment  $alignment): self
    {
        $this->alignment = $alignment;
        return $this;
    }

    private function resolveTextArea(Area $area, Buffer $buffer): Area
    {
        if ($this->block) {
            $inner = $this->block->inner($area);
            $this->block->render($area, $buffer);
            return $inner;
        }

        return $area;
    }

    /**
     * @param list<array{list<StyledGrapheme>,HorizontalAlignment}> $styled
     */
    private function createLineComposer(array $styled, Area $textArea, ?Wrap $wrap, int $horizontalOffset): LineComposer
    {
        return new LineTruncator($styled, $textArea->width, $horizontalOffset);
    }

    private function getLineOffset(int $width, int $maxWidth, HorizontalAlignment $alignment): int
    {
        return match ($alignment) {
            HorizontalAlignment::Center => ($maxWidth / 2) - $width / 2,
            HorizontalAlignment::Right => $maxWidth - $width,
            HorizontalAlignment::Left => 0,
        };
    }

    public function wrap(Wrap $wrap): self
    {
        $this->wrap = $wrap;
        return $this;
    }

    public function block(Block $block): self
    {
        $this->block = $block;
        return $this;
    }
}
