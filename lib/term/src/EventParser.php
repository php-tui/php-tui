<?php

namespace PhpTui\Term;

use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\FocusEvent;
use PhpTui\Term\Event\FunctionKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;

final class EventParser
{
    /**
     * @var string[]
     */
    private array $buffer = [];

    /**
     * @var Event[]
     */
    private array $events = [];

    /**
     * @return Event[]
     */
    public function drain(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    public function advance(string $line, bool $more): void
    {
        // split string into bytes
        $bytes = str_split($line);

        foreach ($bytes as $index => $byte) {
            $more = $index + 1 < strlen($line) || $more;

            $this->buffer[] = $byte;
            try {
                $event = $this->parseEvent($this->buffer, $more);
            } catch (ParseError $error) {
                $this->buffer = [];
                continue;
            }
            if ($event === null) {
                continue;
            }
            $this->events[] = $event;
            $this->buffer = [];
        }
    }

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param string[] $buffer
     */
    private function parseEvent(array $buffer, bool $inputAvailable): ?Event
    {
        if ($buffer === []) {
            return null;
        }

        return match ($buffer[0]) {
            "\x1B" => $this->parseEsc($buffer, $inputAvailable),
            "\x7F" => CodedKeyEvent::new(KeyCode::Backspace),
            "\r" => CodedKeyEvent::new(KeyCode::Enter),
            "\t" => CodedKeyEvent::new(KeyCode::Tab),
            default => $this->parseUtf8Char($buffer),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseEsc(array $buffer, bool $inputAvailable): ?Event
    {
        if (count($buffer) === 1) {
            if ($inputAvailable) {
                // _could_ be an escape sequence
                return null;
            }

            return CodedKeyEvent::new(KeyCode::Esc);
        }

        return match ($buffer[1]) {
            '[' => $this->parseCsi($buffer),
            "\x1B" => CodedKeyEvent::new(KeyCode::Esc),
            'O' => (function () use ($buffer) {
                if (count($buffer) === 2) {
                    return null;
                }

                return match ($buffer[2]) {
                    'P' => FunctionKeyEvent::new(1),
                    'Q' => FunctionKeyEvent::new(2),
                    'R' => FunctionKeyEvent::new(3),
                    'S' => FunctionKeyEvent::new(4),
                    'H' => CodedKeyEvent::new(KeyCode::Home),
                    'F' => CodedKeyEvent::new(KeyCode::End),
                    'D' => CodedKeyEvent::new(KeyCode::Left),
                    'C' => CodedKeyEvent::new(KeyCode::Right),
                    'A' => CodedKeyEvent::new(KeyCode::Up),
                    'B' => CodedKeyEvent::new(KeyCode::Down),
                    default => throw ParseError::couldNotParseOffset($buffer, 22),
                };
            })(),
            default => $this->parseEvent(array_slice($buffer, 1), $inputAvailable),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsi(array $buffer): ?Event
    {
        if (count($buffer) === 2) {
            return null;
        }

        return match ($buffer[2]) {
            'D' => CodedKeyEvent::new(KeyCode::Left),
            'C' => CodedKeyEvent::new(KeyCode::Right),
            'A' => CodedKeyEvent::new(KeyCode::Up),
            'B' => CodedKeyEvent::new(KeyCode::Down),
            'H' => CodedKeyEvent::new(KeyCode::Home),
            'F' => CodedKeyEvent::new(KeyCode::End),
            'Z' => CodedKeyEvent::new(KeyCode::BackTab, KeyModifiers::SHIFT, KeyEventKind::Press),
            'I' => FocusEvent::gained(),
            'O' => FocusEvent::lost(),
            // https://sw.kovidgoyal.net/kitty/keyboard-protocol/#legacy-functional-keys
            'P' => FunctionKeyEvent::new(1),
            'Q' => FunctionKeyEvent::new(2),
            'R' => FunctionKeyEvent::new(3), // this is omitted from crossterm
            'S' => FunctionKeyEvent::new(4),
            ';' => $this->parseCsiModifierKeyCode($buffer),
            '0','1','2','3','4','5','6','7','8','9' => $this->parseCsiMore($buffer),
            default => throw ParseError::couldNotParseOffset($buffer, 2),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsiMore(array $buffer): ?Event
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
            '~' => $this->parseCsiSpecialKeyCode($buffer),
            default => $this->parseCsiModifierKeyCode($buffer),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsiSpecialKeyCode(array $buffer): Event
    {
        $str = implode('', array_slice($buffer, 2, (int)array_key_last($buffer)));

        $split = array_map(
            fn (string $substr) => self::filterToInt($substr) ?? 0,
            explode(';', $str),
        );
        $first = $split[array_key_first($split)];

        $keycode = match ($first) {
            1,7 => KeyCode::Home,
            2 => KeyCode::Insert,
            4,8 => KeyCode::End,
            5 => KeyCode::PageUp,
            6 => KeyCode::PageDown,
            3 => KeyCode::Delete,
            default => null,
        };
        if (null !== $keycode) {
            return CodedKeyEvent::new($keycode);
        }
        return match($first) {
            11,12,13,14,15 => FunctionKeyEvent::new($first - 10),
            17,18,19,20,21 => FunctionKeyEvent::new($first - 11),
            23,24,25,26 => FunctionKeyEvent::new($first - 12),
            28,29 => FunctionKeyEvent::new($first - 15),
            31,32,33,34 => FunctionKeyEvent::new($first - 17),
            default => throw new ParseError(
                sprintf(
                    'Could not parse char "%s" in CSI event: %s',
                    $first,
                    json_encode(implode('', $buffer))
                )
            ),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseUtf8Char(array $buffer): Event
    {
        if (count($buffer) !== 1) {
            throw new ParseError('Multibyte characters not supported');
        }
        $char = $buffer[0];
        return $this->charToEvent($char);
    }

    private function charToEvent(string $char): Event
    {
        $modifiers = 0;
        if (strtoupper($char) === $char) {
            $modifiers = KeyModifiers::SHIFT;
        }

        return CharKeyEvent::new($char, $modifiers);
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsiModifierKeyCode(array $buffer): Event
    {
        $str = implode('', array_slice($buffer, 2, (int)array_key_last($buffer)));
        // split string into bytes
        $parts = explode(';', $str);

        [$modifiers, $kind] = (function () use ($parts) {
            $modifierAndKindCode  = $this->modifierAndKindParsed($parts);
            if (null !== $modifierAndKindCode) {
                return [
                    $this->parseModifiers($modifierAndKindCode[0]),
                    $this->parseKeyEventKind($modifierAndKindCode[1]),
                ];
            }

            // TODO: if buffer.len > 3

            return [KeyModifiers::NONE, KeyEventKind::Press];
        })();

        $key = $buffer[array_key_last($buffer)];
        $codedKey = match ($key) {
            'A' => KeyCode::Up,
            'B' => KeyCode::Down,
            'C' => KeyCode::Right,
            'D' => KeyCode::Left,
            'F' => KeyCode::End,
            'H' => KeyCode::Home,
            default => null,
        };
        if (null !== $codedKey) {
            return CodedKeyEvent::new($codedKey, $modifiers, $kind);
        }
        $fNumber = match ($key) {
            'P' => 1,
            'Q' => 2,
            'R' => 3,
            'S' => 4,
            default => null,
        };
        if (null !== $fNumber) {
            return FunctionKeyEvent::new($fNumber, $modifiers, $kind);
        }

        throw new ParseError('Could not parse event');
    }

    /**
     * @param string[] $parts
     * @return ?array{int,int}
     */
    private function modifierAndKindParsed(array $parts): ?array
    {
        if (!isset($parts[1])) {
            throw new ParseError('Could not parse modifier');
        }
        $parts = explode(':', $parts[1]);
        $modifierMask = self::filterToInt($parts[0]);
        if (null === $modifierMask) {
            return null;
        }
        if (isset($parts[1])) {
            $kindCode = self::filterToInt($parts[1]);
            if (null === $kindCode) {
                return null;
            }
            return [$modifierMask, $kindCode];
        }
        return [$modifierMask, 1];
    }

    private static function filterToInt(string $substr): ?int
    {
        $str = array_reduce(
            str_split($substr),
            function (string $ac, string $char) {
                if (false === is_numeric($char)) {
                    return $ac;
                }
                return $ac . $char;
            },
            ''
        );
        if ($str === '') {
            return null;
        }
        return intval($str);
    }

    /**
     * @return int-mask-of<KeyModifiers::*>
     */
    private function parseModifiers(int $mask): int
    {
        $modifierMask = max(0, $mask - 1);
        $modifiers = KeyModifiers::NONE;
        if (($modifierMask & 1) !== 0) {
            $modifiers |= KeyModifiers::SHIFT;
        }
        if (($modifierMask & 2) !== 0) {
            $modifiers |= KeyModifiers::ALT;
        }
        if (($modifierMask & 4) !== 0) {
            $modifiers |= KeyModifiers::CONTROL;
        }
        if (($modifierMask & 8) !== 0) {
            $modifiers |= KeyModifiers::SUPER;
        }
        if (($modifierMask & 16) !== 0) {
            $modifiers |= KeyModifiers::HYPER;
        }
        if (($modifierMask & 32) !== 0) {
            $modifiers |= KeyModifiers::META;
        }
        return $modifiers;
    }

    private function parseKeyEventKind(int $kind): KeyEventKind
    {
        return match ($kind) {
            1 => KeyEventKind::Press,
            2 => KeyEventKind::Repeat,
            3 => KeyEventKind::Release,
            default => KeyEventKind::Press,
        };
    }
}
