<?php

namespace PhpTui\Term;

use PhpTui\Term\Event\KeyCode;
use PhpTui\Term\Event\KeyEvent;

class EventParser
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
                if ($event === null) {
                    continue;
                }
                $this->events[] = $event;
            } catch (ParseError $error) {
                continue;
            }
        }
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
            "\x7F" => KeyEvent::new(KeyCode::Backspace),
            "\r" => KeyEvent::new(KeyCode::Enter),
            default => throw new ParseError(sprintf('TODO: cannot handle first byte "%s"', $buffer[0])),
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

            return KeyEvent::new(KeyCode::Esc);
        }

        return match ($buffer[1]) {
            '[' => $this->parseCsi($buffer),
            default => throw new ParseError(sprintf('TODO: Could not handle second byte: %s', $buffer[1])),
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
            'D' => KeyEvent::new(KeyCode::Left),
            'C' => KeyEvent::new(KeyCode::Right),
            'A' => KeyEvent::new(KeyCode::Up),
            'B' => KeyEvent::new(KeyCode::Down),
            default => throw new ParseError(sprintf('TODO: Could not handle CSI byte: %s', $buffer[2])),
        };
    }
}
