<?php

namespace PhpTui\Term;

/**
 * Parse ANSI escape sequences (back) to painter actions.
 */
final class OutputParser
{
    /**
     * @var string[]
     */
    private array $buffer = [];

    /**
     * @var Action[]
     */
    private array $actions = [];

    public function __construct(private bool $throw = false)
    {
    }

    /**
     * @return Action[]
     */
    public function drain(): array
    {
        $actions = $this->actions;
        $this->actions = [];
        return $actions;
    }

    public function advance(string $line, bool $more): void
    {
        // split string into bytes
        $chars = mb_str_split($line);

        foreach ($chars as $index => $char) {
            $more = $index + 1 < strlen($line) || $more;

            $this->buffer[] = $char;
            try {
                $action = $this->parseAction($this->buffer, $more);
            } catch (ParseError $error) {
                if ($this->throw) {
                    throw $error;
                }
                $this->buffer = [];
                continue;
            }
            if ($action === null) {
                continue;
            }
            $this->actions[] = $action;
            $this->buffer = [];
        }
    }

    /**
     * @param string[] $buffer
     */
    private function parseAction(array $buffer, bool $more): ?Action
    {
        return match ($buffer[0]) {
            "\x1B" => $this->parseEsc($buffer, $more),
            default => Actions::printString($buffer[0])
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseEsc(array $buffer, bool $more): ?Action
    {
        if (count($buffer) === 1) {
            return null;
        }

        return match ($buffer[1]) {
            '[' => $this->parseCsi($buffer, $more),
            default => Actions::printString($buffer[0])
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsi(array $buffer, bool $more): ?Action
    {
        if (count($buffer) === 2) {
            return null;
        }

        return match ($buffer[2]) {
            '0','1','2','3','4','5','6','7','8','9' => $this->parseCsiSeq($buffer),
            default => throw new ParseError('Could not parse CSI sequence: %s', json_encode(implode('', $buffer))),
        };

    }

    /**
     * @param string[] $buffer
     */
    private function parseCsiSeq(array $buffer): ?Action
    {
        // numbered escape code
        if (count($buffer) === 3) {
            return null;
        }

        $lastByte = $buffer[array_key_last($buffer)];
        // the final byte of a CSI sequence can be in the range 64-126
        $ord = ord($lastByte);
        if ($ord < 64 || $ord > 126) {
            return null;
        }

        return match ($lastByte) {
            'm' => $this->parseGraphicsMode($buffer),
            default => throw new ParseError(sprintf(
                'Do not know how to parse CSI sequence: %s',
                json_encode(implode('', $buffer))
            ))
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseGraphicsMode(array $buffer): ?Action
    {
        $string = implode('', array_slice($buffer, 2, -1));
        $parts = explode(';', $string);

        // true colors
        if (count($parts) === 5) {
            $rgb = array_map(fn (string $index) => intval($index), array_slice($parts, -3));
            return match ($parts[0]) {
                '48' => Actions::setRgbBackgroundColor(...$rgb),
                '38' => Actions::setRgbForegroundColor(...$rgb),
                default => throw new ParseError(sprintf('Could not parse graphics mode: %s', json_encode(implode('', $buffer)))),
            };
        }

        // 256 or ANSI colors
        return match ($parts[0]) {
            '48' => Actions::setRgbBackgroundColor(...Colors256::indexToRgb(intval($parts[2]))),
            '38' => Actions::setRgbForegroundColor(...Colors256::indexToRgb(intval($parts[2]))),
            default => throw new ParseError(sprintf('Could not parse graphics mode: %s', json_encode(implode('', $buffer)))),
        };
    }
}
