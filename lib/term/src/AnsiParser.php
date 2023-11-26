<?php

declare(strict_types=1);

namespace PhpTui\Term;

use PhpTui\Term\Action\AlternateScreenEnable;
use PhpTui\Term\Action\Clear;
use PhpTui\Term\Action\PrintString;

/**
 * Parse ANSI escape sequences (back) to painter actions.
 *
 * Note this is primarily only intended to support ANSI escape sequences
 * emitted by this library (i.e. the Painter actions).
 */
final class AnsiParser
{
    /**
     * @var string[]
     */
    private array $buffer = [];

    /**
     * @var Action[]
     */
    private array $actions = [];

    public function __construct(private readonly bool $throw = false)
    {
    }

    /**
     * @return Action[]
     */
    public function drain(): array
    {
        $actions = $this->actions;
        $this->actions = [];
        $strings = [];

        // compress strings
        $newActions = [];
        foreach ($actions as $action) {
            if ($action instanceof PrintString) {
                $strings[] = $action;

                continue;
            }
            if ($strings) {
                $newActions[] = Actions::printString(
                    implode('', array_map(static fn (PrintString $s): string => $s->string, $strings))
                );
                $strings = [];
            }
            $newActions[] = $action;
        }
        if ($strings) {
            $newActions[] = Actions::printString(
                implode('', array_map(static fn (PrintString $s): string => $s->string, $strings))
            );
        }

        return $newActions;
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
     * @return Action[]
     */
    public static function parseString(string $output, bool $throw = false): array
    {
        $parser = new self($throw);
        $parser->advance($output, true);

        return $parser->drain();
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
            '?' => $this->parsePrivateModes($buffer),
            'J' => Actions::clear(ClearType::FromCursorDown),
            'K' => Actions::clear(ClearType::UntilNewLine),
            default => throw new ParseError(sprintf('Could not parse CSI sequence: %s', json_encode(implode('', $buffer)))),
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
            'H' => $this->parseCursorPosition($buffer),
            'J', 'K' => $this->parseClear($buffer),
            'n' => Actions::requestCursorPosition(),
            default => throw new ParseError(sprintf(
                'Do not know how to parse CSI sequence: %s',
                json_encode(implode('', $buffer))
            ))
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseGraphicsMode(array $buffer): Action
    {
        $string = implode('', array_slice($buffer, 2, -1));
        $parts = explode(';', $string);

        // true colors
        if (count($parts) === 5) {
            $rgb = array_map(static fn (string $index): int => (int) $index, array_slice($parts, -3));

            return match ($parts[0]) {
                '48' => Actions::setRgbBackgroundColor(...$rgb),
                '38' => Actions::setRgbForegroundColor(...$rgb),
                default => throw new ParseError(sprintf('Could not parse graphics mode: %s', json_encode(implode('', $buffer)))),
            };
        }
        if (count($parts) === 3) {
            return match ($parts[0]) {
                '48' => Actions::setRgbBackgroundColor(...Colors256::indexToRgb((int) ($parts[2]))),
                '38' => Actions::setRgbForegroundColor(...Colors256::indexToRgb((int) ($parts[2]))),
                default => throw new ParseError(sprintf('Could not parse graphics mode: %s', json_encode(implode('', $buffer)))),
            };
        }

        $code = intval($parts[0]);

        return match ($parts[0]) {
            '39' => Actions::setForegroundColor(Colors::Reset),
            '49' => Actions::setBackgroundColor(Colors::Reset),
            '1' => Actions::bold(true),
            '22' => Actions::bold(false),
            '2' => Actions::dim(true),
            '22' => Actions::dim(false),
            '3' => Actions::italic(true),
            '23' => Actions::italic(false),
            '4' => Actions::underline(true),
            '24' => Actions::underline(false),
            '5' => Actions::slowBlink(true),
            '25' => Actions::slowBlink(false),
            '7' => Actions::reverse(true),
            '27' => Actions::reverse(false),
            '8' => Actions::hidden(true),
            '28' => Actions::hidden(false),
            '9' => Actions::strike(true),
            '29' => Actions::strike(false),
            '0' => Actions::reset(),
            default => match (true) {
                substr($parts[0], 0, 1) === '3' => Actions::setForegroundColor($this->inverseColorIndex($code, false)),
                substr($parts[0], 0, 1) === '4' => Actions::setBackgroundColor($this->inverseColorIndex($code, true)),
                substr($parts[0], 0, 1) === '9' => Actions::setForegroundColor($this->inverseColorIndex($code, false)),
                substr($parts[0], 0, 2) === '10' => Actions::setBackgroundColor($this->inverseColorIndex($code, true)),
                default => throw new ParseError(sprintf('Could not parse graphics mode: %s', json_encode(implode('', $buffer)))),
            },
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parsePrivateModes(array $buffer): ?Action
    {
        $last = $buffer[array_key_last($buffer)];
        if (count($buffer) === 3) {
            return null;
        }

        return match ($buffer[3]) {
            '2' => $this->parsePrivateModes2($buffer),
            '1' => $this->parsePrivateModes2($buffer),
            default => throw new ParseError(sprintf('Could not parse graphics mode: %s', json_encode(implode('', $buffer)))),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parsePrivateModes2(array $buffer): ?Action
    {
        if (count($buffer) === 4) {
            return null;
        }
        if (count($buffer) === 5) {
            return null;
        }

        return match ($buffer[4]) {
            '5' => match ($buffer[5]) {
                'l' => Actions::cursorHide(),
                'h' => Actions::cursorShow(),
                default => throw ParseError::couldNotParseBuffer($buffer),
            },
            '0' => match ($buffer[5]) {
                '4' => (function () use ($buffer): ?AlternateScreenEnable {
                    if (count($buffer) === 6 || count($buffer) === 7) {
                        return null;
                    }

                    return match ($buffer[7]) {
                        'h' => Actions::alternateScreenEnable(),
                        'l' => Actions::alternateScreenDisable(),
                        default => throw ParseError::couldNotParseOffset($buffer, 7),
                    };
                })(),
                default => throw ParseError::couldNotParseOffset($buffer, 5),
            },
            default => throw ParseError::couldNotParseOffset($buffer, 4),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCursorPosition(array $buffer): Action
    {
        $string = implode('', array_slice($buffer, 2, -1));
        $parts = explode(';', $string);
        if (count($parts) !== 2) {
            throw new ParseError(sprintf('Could not parse cursor position from: "%s"', $string));
        }

        return Actions::moveCursor((int) ($parts[0]), (int) ($parts[1]));
    }

    /**
     * @param string[] $buffer
     */
    private function parseClear(array $buffer): Action
    {
        array_shift($buffer);
        array_shift($buffer);
        $clear = implode('', $buffer);

        return new Clear(match ($clear) {
            '2J' => ClearType::All,
            '3J' => ClearType::Purge,
            'J' => ClearType::FromCursorDown,
            '1J' => ClearType::FromCursorUp,
            '2K' => ClearType::CurrentLine,
            'K' => ClearType::UntilNewLine,
            default => throw new ParseError(sprintf(
                'Could not parse clear "%s"',
                $clear
            )),
        });
    }

    private function inverseColorIndex(int $color, bool $background): Colors
    {
        $color -= $background ? 10 : 0;
        return match ($color) {
            30 => Colors::Black,
            31 => Colors::Red,
            32 => Colors::Green,
            33 => Colors::Yellow,
            34 => Colors::Blue,
            35 => Colors::Magenta,
            36 => Colors::Cyan,
            37 => Colors::Gray,
            90 => Colors::DarkGray,
            91 => Colors::LightRed,
            92 => Colors::LightGreen,
            93 => Colors::LightYellow,
            94 => Colors::LightBlue,
            95 => Colors::LightMagenta,
            96 => Colors::LightCyan,
            97 => Colors::White,
            default => throw new ParseError(sprintf('Do not know how to handle color: %s', $color)),
        };
    }
}
