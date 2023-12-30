<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Display;

use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Display\Backend\DummyBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\PointsShape;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Extension\Core\Widget\BufferWidget;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Position\Position;
use PHPUnit\Framework\TestCase;

final class DisplayTest extends TestCase
{
    public function testAutoresize(): void
    {
        $backend = DummyBackend::fromDimensions(4, 4);
        $terminal = DisplayBuilder::default($backend)->build();
        $backend->setDimensions(2, 2);

        // intentionally go out of bounds
        $terminal->draw(new BufferWidget(static function (BufferContext $context): void {
            for ($y = 0; $y < 4; $y++) {
                for ($x = 0; $x < 4; $x++) {
                    $context->buffer->putString(new Position($x, $y), 'h');
                }
            }
        }));
        self::assertEquals(<<<'EOT'
            hh  
            hh  
                
                
            EOT, $backend->toString());
    }

    public function testDraw(): void
    {
        $backend = DummyBackend::fromDimensions(4, 4);
        $terminal = DisplayBuilder::default($backend)->build();
        $terminal->draw(new BufferWidget(static function (BufferContext $context): void {
            $x = 0;
            for ($y = 0; $y <= 4; $y++) {
                $context->buffer->putString(new Position($x++, $y), 'x');
            }
        }));
        self::assertEquals(
            <<<'EOT'
                x   
                 x  
                  x 
                   x
                EOT,
            $backend->flushed()
        );
    }

    public function testRender(): void
    {
        $backend = DummyBackend::fromDimensions(4, 4);
        $terminal = DisplayBuilder::default($backend)->build();
        $terminal->draw(CanvasWidget::fromIntBounds(0, 3, 0, 3)->marker(Marker::Dot)->draw(PointsShape::new([
            [3, 3], [2, 2], [1, 1], [0, 0]
        ], AnsiColor::Green)));

        self::assertEquals(
            <<<'EOT'
                   •
                  • 
                 •  
                •   
                EOT,
            $backend->flushed()
        );
    }

    public function testFlushes(): void
    {
        $backend = DummyBackend::fromDimensions(10, 4);
        $terminal = DisplayBuilder::default($backend)->build();
        $terminal->buffer()->putString(new Position(2, 1), 'X');
        $terminal->buffer()->putString(new Position(0, 0), 'X');
        $terminal->flush();
        self::assertEquals(
            implode("\n", [
                'X         ',
                '  X       ',
                '          ',
                '          ',
            ]),
            $backend->toString()
        );
    }

    public function testInlineViewport(): void
    {
        $backend = new DummyBackend(10, 10, Position::at(0, 15));
        $terminal = DisplayBuilder::default($backend)->inline(10)->build();
        $terminal->draw(ParagraphWidget::fromString('Hello'));

        self::assertEquals(6, $terminal->viewportArea()->top());
        self::assertEquals(0, $terminal->viewportArea()->left());
    }

    public function testFixedViewport(): void
    {
        $backend = new DummyBackend(10, 10, Position::at(0, 15));
        $terminal = DisplayBuilder::default($backend)->fixed(1, 2, 20, 15)->build();
        $terminal->draw(ParagraphWidget::fromString('Hello'));

        self::assertEquals(1, $terminal->viewportArea()->position->x);
        self::assertEquals(2, $terminal->viewportArea()->position->y);
        self::assertEquals(2, $terminal->viewportArea()->top());
        self::assertEquals(1, $terminal->viewportArea()->left());
        self::assertEquals(21, $terminal->viewportArea()->right());
        self::assertEquals(17, $terminal->viewportArea()->bottom());
    }

    public function testInsertBefore(): void
    {
        $backend = new DummyBackend(15, 10, Position::at(0, 0));
        $terminal = DisplayBuilder::default($backend)->inline(2)->build();
        $terminal->insertBefore(2, ParagraphWidget::fromString(
            <<<'EOT'
                Before
                World
                EOT
        ));
        $terminal->draw(ParagraphWidget::fromString('Hello World'));
        self::assertEquals(
            <<<'EOT'
                Before         
                World          
                Hello World    
                               
                               
                               
                               
                               
                               
                               
                EOT,
            $backend->toString()
        );

    }
}
