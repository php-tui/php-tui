<?php

declare(strict_types=1);

namespace PhpTui\Tui\Text\LineComposer;

use Generator;
use PhpTui\Tui\Text\LineComposer;
use PhpTui\Tui\Text\StyledGrapheme;
use PhpTui\Tui\Widget\HorizontalAlignment;

final class WordWrapper implements LineComposer
{
    private const NBSP = "\u{00a0}";

    /**
     * @param list<array{list<StyledGrapheme>,HorizontalAlignment}> $lines
     */
    public function __construct(
        private readonly array $lines,
        private readonly int $maxLineWidth,
        private readonly bool $trim = false,
    ) {
    }

    public function nextLine(): Generator
    {
        if ($this->maxLineWidth === 0) {
            return;
        }

        $wrappedLines = [];
        foreach ($this->lines as $line) {
            /**
             * @var StyledGrapheme[] $lineSymbols
             * @var HorizontalAlignment $lineAlignment
             */
            [$lineSymbols, $lineAlignment] = $line;
            [$currentLine, $currentLineWidth] = [[], 0];
            [$currentWord, $currentWordWidth] = [[], 0];
            [$whitespacesBuffer, $whitespacesWidth] = [[], 0];
            $hasSeenNonWhitespace = false;

            foreach ($lineSymbols as $symbol) {
                $isWhitespace = $this->isWhitespace($symbol);
                $symbolWidth = $symbol->symbolWidth();
                if ($symbolWidth > $this->maxLineWidth) {
                    continue;
                }

                // Append finished word to current line
                if (
                    $hasSeenNonWhitespace && $isWhitespace
                    // Append if trimmed (whitespaces removed) word would overflow
                    || $this->trim && ($currentWordWidth + $symbolWidth) > $this->maxLineWidth && $currentLine === []
                    // Append if removed whitespace would overflow -> reset whitespace counting to prevent overflow
                    || $this->trim && ($whitespacesWidth + $symbolWidth) > $this->maxLineWidth && $currentLine === []
                    // Append if complete word would overflow
                    || !$this->trim && ($currentWordWidth + $whitespacesWidth + $symbolWidth) > $this->maxLineWidth && $currentLine === []
                ) {
                    if ($currentLine !== [] || !$this->trim) {
                        // Also append whitespaces if not trimming or current line is not empty
                        $currentLine = [...$currentLine, ...$whitespacesBuffer];
                        $currentLineWidth += $whitespacesWidth;
                    }

                    // Append trimmed word
                    $currentLine = [...$currentLine, ...$currentWord];
                    $currentLineWidth += $currentWordWidth;
                    $currentWord = [];

                    // Clear whitespace buffer
                    $whitespacesBuffer = [];
                    $whitespacesWidth = 0;
                    $currentWordWidth = 0;
                }

                if (
                    // Append the unfinished wrapped line to wrapped lines if it is as wide as max line width
                    $currentLineWidth >= $this->maxLineWidth
                    // or if it would be too long with the current partially processed word added
                    || ($currentLineWidth + $whitespacesWidth + $currentWordWidth) >= $this->maxLineWidth && $symbolWidth > 0
                ) {
                    $remainingWidth = max($this->maxLineWidth - $currentLineWidth, 0);

                    $wrappedLines[] = $this->processLine($currentLine, $lineAlignment);
                    $currentLine = [];
                    $currentLineWidth = 0;

                    // Remove all whitespaces till end of just appended wrapped line + next whitespace
                    $this->removeWhitespaces($whitespacesBuffer, $whitespacesWidth, $remainingWidth);

                    // In case all whitespaces have been exhausted, prevent first whitespace to count towards next word
                    if ($isWhitespace) {
                        continue;
                    }
                }

                // Append symbol to unfinished, partially processed word
                if ($isWhitespace) {
                    $whitespacesBuffer[] = $symbol;
                    $whitespacesWidth += $symbolWidth;
                } else {
                    $currentWord[] = $symbol;
                    $currentWordWidth += $symbolWidth;
                }

                $hasSeenNonWhitespace = !$isWhitespace;
            }

            // Append remaining text parts
            if ($currentWord !== [] || $whitespacesBuffer !== []) {
                if ($currentLine === [] && $currentWord === []) {
                    $wrappedLines[] = $this->processLine([], $lineAlignment);
                } elseif (!$this->trim || $currentLine !== []) {
                    $currentLine = [...$currentLine, ...$whitespacesBuffer];
                    $whitespacesBuffer = [];
                }
                $currentLine = [...$currentLine, ...$currentWord];
            }

            // Append remaining line
            if ($currentLine !== []) {
                $wrappedLines[] = $this->processLine($currentLine, $lineAlignment);
            }

            // Append empty line if there was nothing to wrap in the first place
            if ($wrappedLines === []) {
                $wrappedLines[] = $this->processLine([], $lineAlignment);
            }
        }

        yield from $wrappedLines;
    }

    /**
     * @param StyledGrapheme[] $currentLine
     * @return array{list<StyledGrapheme>,int,HorizontalAlignment}
     */
    private function processLine(array $currentLine, HorizontalAlignment $alignment): array
    {
        $lineWidth = array_reduce($currentLine, static function (int $width, StyledGrapheme $grapheme): int {
            return $width + $grapheme->symbolWidth();
        }, 0);

        return [$currentLine, $lineWidth, $alignment];
    }

    /**
     * @param StyledGrapheme[] $whitespaceBuffer
     */
    private function removeWhitespaces(array &$whitespaceBuffer, int &$whitespacesWidth, int &$remainingWidth): void
    {
        $whitespace = array_shift($whitespaceBuffer);
        while ($whitespace instanceof StyledGrapheme) {
            $symbolWidth = $whitespace->symbolWidth();
            $whitespacesWidth -= $symbolWidth;
            if ($symbolWidth > $remainingWidth) {
                break;
            }
            $remainingWidth -= $symbolWidth;
            $whitespace = array_shift($whitespaceBuffer);
        }
    }

    private function isWhitespace(StyledGrapheme $symbol): bool
    {
        return preg_match('/\p{Z}/u', $symbol->symbol) && $symbol->symbol !== self::NBSP;
    }
}
