<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\LineComposer;
use PhpTui\Tui\Model\LineComposer\LineTruncator;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\StyledGrapheme;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Paragraph\Wrap;

class Paragraph implements Widget
{
    /** @param array{int,int} $scroll */
    private function __construct(
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
        $textArea = $area;

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

    public function wrap(Wrap $wrap): self
    {
        $this->wrap = $wrap;
        return $this;
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
            HorizontalAlignment::Center => intval(($maxWidth / 2) - $width / 2),
            HorizontalAlignment::Right => $maxWidth - $width,
            HorizontalAlignment::Left => 0,
        };
    }
}
