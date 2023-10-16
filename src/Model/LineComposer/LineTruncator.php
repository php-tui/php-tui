<?php

namespace DTL\PhpTui\Model\LineComposer;

use DTL\PhpTui\Model\LineComposer;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Model\Widget\StyledGrapheme;
use Generator;

class LineTruncator implements LineComposer
{
    /**
     * @param list<array{list<StyledGrapheme>, HorizontalAlignment}> $lines
     */
    public function __construct(
        private array $lines,
        private int $maxLineWidth,
        private int $horizontalOffset = 0
    ) {
    }

    public function nextLine(): Generator
    {
        if ($this->maxLineWidth === 0) {
            return;
        }

        $currentLineWidth = 0;
        $linesExhausted = true;
        $horizontalOffset = $this->horizontalOffset;
        $currentAlignment = HorizontalAlignment::Left;
        foreach ($this->lines as $line) {
            $linesExhausted = false;
            [$currentLine, $alignment] = $line;
            $currentAlignment = $alignment;

            /** @var StyledGrapheme $styledGrapheme */
            foreach ($currentLine as $styledGrapheme) {
                // ignore characters wider than the total max width
                if ($styledGrapheme->symbolWidth() > $this->maxLineWidth) {
                    continue;
                }

                if ($currentLineWidth + $styledGrapheme->symbolWidth() > $this->maxLineWidth) {
                    yield [
                        $this->currentLine,
                        $currentLineWidth,
                        $currentAlignment
                    ];
                    continue;
                }

                $symbol = $this->resolveSymbol(
                    $horizontalOffset,
                    $alignment,
                    $styledGrapheme,
                );

                $currentLineWidth += mb_strlen($symbol);
                $this->currentLine[] = new StyledGrapheme($symbol, $styledGrapheme->style);
            }
        }

        yield [
            $this->currentLine,
            $currentLineWidth,
            $currentAlignment
        ];
    }

    private function resolveSymbol(int &$horizontalOffset, HorizontalAlignment $alignment, StyledGrapheme $styledGrapheme): string
    {
        if ($horizontalOffset === 0 || HorizontalAlignment::Left !== $alignment) {
            return $styledGrapheme->symbol;
        }

        if ($styledGrapheme->symbolWidth() > $horizontalOffset) {
            $symbol = self::trimOffset($styledGrapheme->symbol, $horizontalOffset);
            $horizontalOffset = 0;
            return $symbol;
        }

        return '';
    }
}
