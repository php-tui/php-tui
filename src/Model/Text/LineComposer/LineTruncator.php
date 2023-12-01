<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Text\LineComposer;

use Generator;
use PhpTui\Tui\Model\Text\LineComposer;
use PhpTui\Tui\Model\Text\StyledGrapheme;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;

final class LineTruncator implements LineComposer
{
    /**
     * @param list<array{list<StyledGrapheme>,HorizontalAlignment}> $lines
     */
    public function __construct(
        private readonly array $lines,
        private readonly int $maxLineWidth,
        private int $horizontalOffset = 0,
    ) {
    }

    public function nextLine(): Generator
    {
        if ($this->maxLineWidth === 0) {
            return;
        }
        $currentLineWidth = 0;
        $horizontalOffset = $this->horizontalOffset;
        $currentLine = [];
        $currentAlignment = HorizontalAlignment::Left;

        foreach ($this->lines as $line) {
            /** @var HorizontalAlignment $alignment */
            [$line, $alignment] = $line;

            $currentAlignment = $alignment;

            /** @var StyledGrapheme $styledGrapheme */
            foreach ($line as $styledGrapheme) {
                // ignore characters wider than the total max width
                if ($styledGrapheme->symbolWidth() > $this->maxLineWidth) {
                    continue;
                }

                if ($currentLineWidth + $styledGrapheme->symbolWidth() > $this->maxLineWidth) {
                    yield [
                        $currentLine,
                        $currentLineWidth,
                        $currentAlignment
                    ];
                    $currentLine = [];
                    $currentLineWidth = 0;
                }

                $symbol = $this->resolveSymbol(
                    $horizontalOffset,
                    $alignment,
                    $styledGrapheme,
                );

                $currentLine[] = new StyledGrapheme($symbol, $styledGrapheme->style);
                $currentLineWidth += mb_strlen($symbol);
            }
            yield [
                $currentLine,
                $currentLineWidth,
                $currentAlignment
            ];
            $currentLine = [];
            $currentLineWidth = 0;
        }

    }

    private function resolveSymbol(int &$horizontalOffset, HorizontalAlignment $alignment, StyledGrapheme $styledGrapheme): string
    {
        if ($horizontalOffset === 0 || HorizontalAlignment::Left !== $alignment) {
            return $styledGrapheme->symbol;
        }

        if ($styledGrapheme->symbolWidth() > $horizontalOffset) {
            $symbol = $this->trimOffset($styledGrapheme->symbol, $horizontalOffset);
            $horizontalOffset = 0;

            return $symbol;
        }
        $horizontalOffset -= $styledGrapheme->symbolWidth();

        return '';
    }

    private function trimOffset(string $string, int $horizontalOffset): string
    {
        return mb_substr($string, 0, $horizontalOffset);
    }
}
