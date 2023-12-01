<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Paragraph\Wrap;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\LineComposer;
use PhpTui\Tui\Text\LineComposer\LineTruncator;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Text\StyledGrapheme;
use PhpTui\Tui\Widget\HorizontalAlignment;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class ParagraphRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        if (!$widget instanceof ParagraphWidget) {
            return;
        }
        $buffer->setStyle(null, $widget->style);
        $textArea = $area;

        if ($textArea->height < 1) {
            return;
        }

        $widget->text->patchStyle($widget->style);
        $style = $widget->style;
        $styled = array_map(function (Line $line) use ($style, $widget): array {
            $graphemes = array_reduce($line->spans, function (array $ac, Span $span) use ($style): array {
                foreach ($span->toStyledGraphemes($style) as $grapheme) {
                    $ac[] = $grapheme;
                }

                return $ac;
            }, []);

            return [ $graphemes, $line->alignment ?? $widget->alignment ];
        }, $widget->text->lines);

        $lineComposer = $this->createLineComposer(
            $styled,
            $textArea,
            $widget->wrap,
            $widget->scroll[1]
        );

        $y = 0;
        foreach ($lineComposer->nextLine() as $line) {
            [$currentLine, $currentLineWidth, $currentLineAlignment] = $line;

            if ($y >= $widget->scroll[0]) {

                $x = $this->getLineOffset($currentLineWidth, $textArea->width, $currentLineAlignment);

                foreach ($currentLine as $grapheme) {
                    if ($grapheme->symbolWidth() === 0) {
                        continue;
                    }
                    $cell = $buffer->get(Position::at(
                        $textArea->left() + $x,
                        $textArea->top() + max(0, $y - $widget->scroll[0])
                    ));
                    $cell->setChar($grapheme->symbol === '' ? ' ' : $grapheme->symbol);
                    $cell->setStyle($grapheme->style);
                    $x += $grapheme->symbolWidth();
                }
            }

            $y += 1;
            if ($y >= $textArea->height + $widget->scroll[0]) {
                break;
            }
        }
    }

    /**
     * @param list<array{list<StyledGrapheme>,HorizontalAlignment}> $styled
     */
    private function createLineComposer(array $styled, Area $textArea, ?Wrap $wrap, int $horizontalOffset): LineComposer
    {
        return new LineTruncator($styled, $textArea->width, $horizontalOffset);
    }

    /**
     * @return int<0,max>
     */
    private function getLineOffset(int $width, int $maxWidth, HorizontalAlignment $alignment): int
    {
        return match ($alignment) {
            HorizontalAlignment::Center => max(0, (int) (($maxWidth / 2) - $width / 2)),
            HorizontalAlignment::Right => max(0, $maxWidth - $width),
            HorizontalAlignment::Left => 0,
        };
    }
}
