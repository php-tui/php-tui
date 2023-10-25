<?php

namespace PhpTui\Tui\Example\Demo;

use PhpTui\Term\Actions;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Example\Demo\Page\CanvasPage;
use PhpTui\Tui\Example\Demo\Page\ChartPage;
use PhpTui\Tui\Example\Demo\Page\EventsPage;
use PhpTui\Tui\Example\Demo\Page\ItemListPage;
use PhpTui\Tui\Example\Demo\Page\TablePage;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Layout;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Paragraph;

final class App
{
    /**
     * @param array<string,Component> $pages
     * @param int[] $frameRate
     */
    private function __construct(
        private Terminal $terminal,
        private Display $display,

        private ActivePage $activePage,
        private array $pages,
        private array $frameRate,
    )
    {
    }

    public static function new(): self
    {
        $terminal = Terminal::new();
        return new self(
            $terminal,
            Display::fullscreen(PhpTermBackend::new($terminal)),
            ActivePage::Events,
            [
                ActivePage::Events->name => new EventsPage(),
                ActivePage::Canvas->name => new CanvasPage(),
                ActivePage::Chart->name => new ChartPage(),
                ActivePage::List->name => new ItemListPage(),
                ActivePage::Table->name => new TablePage(),
            ],
            [],
        );
    }

    public function run(): int
    {
        // enable "raw" mode to remove default terminal behavior (e.g. echoing key presses)
        $this->terminal->enableRawMode();
        // hide the cursor
        $this->terminal->execute(Actions::cursorHide());
        // switch to the "alternate" screen so that we can return the user where they left off
        $this->terminal->execute(Actions::alternateScreenEnable());

        // the main loop
        while (true) {
            // handle events sent to the terminal
            while (null !== $event = $this->terminal->events()->next()) {
                if ($event instanceof CharKeyEvent) {
                    if ($event->char === 'q') {
                        break 2;
                    }
                    if ($event->char === '1') {
                        $this->activePage = ActivePage::Events;
                    }
                    if ($event->char === '2') {
                        $this->activePage = ActivePage::Canvas;
                    }
                    if ($event->char === '3') {
                        $this->activePage = ActivePage::Chart;
                    }
                    if ($event->char === '4') {
                        $this->activePage = ActivePage::List;
                    }
                    if ($event->char === '5') {
                        $this->activePage = ActivePage::Table;
                    }
                }
                $this->activePage()->handle($event);
            }

            $this->display->draw(function (Buffer $buffer): void {
                $this->render($buffer);
            });
            $this->incFramerate();

            usleep(10_000);
        }

        $this->terminal->disableRawMode();
        $this->terminal->execute(Actions::cursorShow());
        $this->terminal->execute(Actions::alternateScreenDisable());

        return 0;
    }

    private function render(Buffer $buffer): void
    {
        $layout = Layout::default()
            ->direction(Direction::Vertical)
            ->constraints([
                Constraint::max(3),
                Constraint::min(1),
            ])
            ->split($buffer->area());

        $this->header()->render($layout->get(0), $buffer);
        $this->activePage()->build()->render($layout->get(1), $buffer);
    }

    private function activePage(): Component
    {
        return $this->pages[$this->activePage->name];
    }

    private function header(): Widget
    {
        return Paragraph::new(Text::fromLine(Line::fromSpans([
            Span::styled('[q]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString('quit '),
            Span::styled('[1]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString('events '),
            Span::styled('[2]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString('canvas '),
            Span::styled('[3]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString('chart '),
            Span::styled('[4]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString('list '),
            Span::styled('[5]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString('table '),
        ])))->block(
            Block::default()
                ->borders(Borders::ALL)->style(Style::default()->fg(AnsiColor::Red))
            ->title(Title::fromString(sprintf('%d FPS', $this->frameRate()))->horizontalAlignmnet(HorizontalAlignment::Right))
        );
    }

    private function incFramerate(): void
    {
        $time = time();
        $this->frameRate[] = time();
    }

    private function frameRate(): float
    {
        if (count($this->frameRate) === 0) {
            return 0.0;
        }

        $time = time();
        foreach ($this->frameRate as $i => $frameRate) {
            if ($frameRate < $time - 2) {
                unset($this->frameRate[$i]);
            }
        }
        $bySecond = array_reduce($this->frameRate, function (array $ac, int $timestamp) {
            if (!isset($ac[$timestamp])) {
                $ac[$timestamp] = 0;
            }
            $ac[$timestamp]++;
            return $ac;
        }, []);

        return array_sum($bySecond) / count($bySecond);
    }
}
