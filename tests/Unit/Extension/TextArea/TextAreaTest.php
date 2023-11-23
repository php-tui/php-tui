<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\TextArea;

use OutOfRangeException;
use PhpTui\Tui\Extension\TextArea\TextArea;
use PhpTui\Tui\Model\Position\Position;
use PHPUnit\Framework\TestCase;

class TextAreaTest extends TestCase
{
    public function testInsert(): void
    {
        $editor = TextArea::fromString('');
        $editor->insert('H');
        $editor->insert('ello');
        $editor->insert(' World');
        self::assertEquals('Hello World', $editor->toString());

        $editor = TextArea::fromString('Hello World');
        $editor->insert('Hello ');
        self::assertEquals('Hello Hello World', $editor->toString());

        $editor = TextArea::fromString('');
        $editor->insert('Hello🐈Cat');
        $editor->insert('Hai');
        self::assertEquals('Hello🐈CatHai', $editor->toString());
    }

    public function testInsertNewLine(): void
    {
        $editor = TextArea::fromString('Hello World');
        $editor->newLine();
        self::assertEquals(<<<'EOT'

        Hello World
        EOT, $editor->toString());
    }

    public function testInsertNewLineBetween(): void
    {
        $editor = TextArea::fromString(<<<'EOT'
        Hello
        World
        EOT);
        $editor->cursorDown();
        $editor->newLine();
        self::assertEquals(<<<'EOT'
        Hello

        World
        EOT, $editor->toString());
    }

    public function testInsertNewLineAtOffset(): void
    {
        $editor = TextArea::fromString(<<<'EOT'
        Hello
        World
        EOT);
        $editor->cursorRight(2);

        self::assertEquals(Position::at(2, 0), $editor->cursorPosition());
        $editor->newLine();

        self::assertEquals(<<<'EOT'
        He
        llo
        World
        EOT, $editor->toString());
        self::assertEquals(Position::at(0, 1), $editor->cursorPosition());
    }

    public function testInsertReplace(): void
    {
        $editor = TextArea::fromString('Hello World');
        $editor->insert('World', 5);

        self::assertEquals('World World', $editor->toString());

        $editor->insert(' Space', 6);
        self::assertEquals('World Space', $editor->toString());
    }

    public function testInsertReplaceNegativeLength(): void
    {
        $this->expectException(OutOfRangeException::class);
        $editor = TextArea::fromString('Hello World');
        /** @phpstan-ignore-next-line */
        $editor->insert('World', -5);
    }

    public function testDelete(): void
    {
        $editor = TextArea::fromString('');
        $editor->insert('Hello');
        $editor->deleteBackwards();
        self::assertEquals('Hell', $editor->toString());
        $editor->deleteBackwards();
        self::assertEquals('Hel', $editor->toString());

        $editor->deleteBackwards(2);
        self::assertEquals('H', $editor->toString());

        $editor = TextArea::fromString('');
        $editor->insert('Hello🐈Cat');
        $editor->deleteBackwards(4);
        self::assertEquals('Hello', $editor->toString());
    }

    public function testDeleteAtZero(): void
    {
        $editor = TextArea::fromString('Hello');
        $editor->deleteBackwards(4);
        self::assertEquals('Hello', $editor->toString());
    }

    public function testNextWord(): void
    {
        $editor = TextArea::fromString('Hello World');
        $editor->wordForward(1);
        self::assertEquals(Position::at(6, 0), $editor->cursorPosition());
        $editor->deleteBackwards();
        self::assertEquals(Position::at(5, 0), $editor->cursorPosition());
        self::assertEquals('HelloWorld', $editor->toString());
    }


    public function testNextWordCallMultipleTimes(): void
    {
        $editor = TextArea::fromString('Hello World This Is Something');
        $editor->wordForward();
        $editor->wordForward();
        $editor->wordForward();
        self::assertEquals(Position::at(17, 0), $editor->cursorPosition());
    }

    public function testNextWordMultiline(): void
    {
        $editor = TextArea::fromString(<<<'EOT'
        Hello
        This
        EOT
        );
        $editor->wordForward(1);
        self::assertEquals(Position::at(0, 1), $editor->cursorPosition());
    }

    public function testNextWordWithCount(): void
    {
        $editor = TextArea::fromString(<<<'EOT'
        Hello World
        This
        EOT
        );
        $editor->wordForward(2);
        self::assertEquals(Position::at(0, 1), $editor->cursorPosition());
    }

    public function testPrevWord(): void
    {
        $editor = TextArea::fromString('Hello World Goodbye');
        $editor->moveCursor(Position::at(15, 0));
        $editor->seekWordPrev();
        $editor->deleteBackwards();
        self::assertEquals('Hello WorldGoodbye', $editor->toString());
    }

    public function testPrevWordMultiline(): void
    {
        $editor = TextArea::fromString(<<<'EOT'
        Hello World
        This
        EOT
        );
        $editor->moveCursor(Position::at(0, 1));
        $editor->seekWordPrev();
        $editor->deleteBackwards();
        self::assertEquals(<<<'EOT'
            HelloWorld
            This
            EOT,
            $editor->toString()
        );
    }


    public function testNavigate(): void
    {
        $editor = TextArea::fromLines([
            '0123',
            '01234567',
        ]);

        self::assertEquals(Position::at(0, 0), $editor->cursorPosition());

        $editor->cursorLeft();
        self::assertEquals(Position::at(0, 0), $editor->cursorPosition());

        $editor->cursorRight();
        self::assertEquals(Position::at(1, 0), $editor->cursorPosition());
        $editor->cursorRight();
        self::assertEquals(Position::at(2, 0), $editor->cursorPosition());
        $editor->cursorRight();
        self::assertEquals(Position::at(3, 0), $editor->cursorPosition());
        $editor->cursorRight();
        self::assertEquals(Position::at(4, 0), $editor->cursorPosition());
        $editor->cursorRight();
        self::assertEquals(Position::at(4, 0), $editor->cursorPosition());

        $editor->cursorLeft();
        $editor->cursorLeft();
        $editor->cursorLeft();
        $editor->cursorLeft();
        self::assertEquals(Position::at(0, 0), $editor->cursorPosition());

        $editor->cursorDown();
        self::assertEquals(Position::at(0, 1), $editor->cursorPosition());

        $editor->cursorDown();
        self::assertEquals(Position::at(0, 1), $editor->cursorPosition());

        $editor->cursorUp();
        $editor->cursorUp();
        self::assertEquals(Position::at(0, 0), $editor->cursorPosition());
    }
}
