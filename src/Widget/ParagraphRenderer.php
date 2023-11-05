<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\LineComposer;
use PhpTui\Tui\Model\LineComposer\LineTruncator;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\StyledGrapheme;
use PhpTui\Tui\Widget\Paragraph\Wrap;

class ParagraphRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof Paragraph) {
            return;
        }
        $buffer->setStyle($area, $widget->style);
        $textArea = $area;

        if ($textArea->height < 1) {
            return;
        }

        $widget->text->patchStyle($widget->style);
        $style = $widget->style;
        $styled = array_map(function (Line $line) use ($style, $widget) {
            $graphemes = array_reduce($line->spans, function (array $ac, Span $span) use ($style) {
                foreach ($span->toStyledGraphemes($style) as $grapheme) {
                    $ac[] = $grapheme;
                }
                return $ac;
            }, []);
            return [ $graphemes, $line->alignment ?: $widget->alignment ];
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

                $x = self::getLineOffset($currentLineWidth, $textArea->width, $currentLineAlignment);

                foreach ($currentLine as $grapheme) {
                    if ($grapheme->symbolWidth() === 0) {
                        continue;
                    }
                    $cell = $buffer->get(Position::at(
                        $textArea->left() + $x,
                        $textArea->top() + $y - $widget->scroll[0]
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

    private static function getLineOffset(int $width, int $maxWidth, HorizontalAlignment $alignment): int
    {
        return match ($alignment) {
            HorizontalAlignment::Center => intval(($maxWidth / 2) - $width / 2),
            HorizontalAlignment::Right => $maxWidth - $width,
            HorizontalAlignment::Left => 0,
        };
    }
}
