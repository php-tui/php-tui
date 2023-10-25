<?php

namespace PhpTui\Tui\Example\Demo;

use PhpTui\Term\Actions;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Example\Demo\Page\Canvas;
use PhpTui\Tui\Example\Demo\Page\Events;
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
     */
    private function __construct(
        private Terminal $terminal,
        private Display $display,

        private ActivePage $activePage,
        private array $pages,
        /** @var array<int,int> */
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
            ActivePage::Home,
            [
                ActivePage::Home->name => new Events(),
                ActivePage::Canvas->name => new Canvas(),
            ],
            [],
        );
    }

    public function run(): int
    {
        $this->terminal->enableRawMode();
        $this->terminal->execute(Actions::cursorHide());
        $this->terminal->execute(Actions::alternateScreenEnable());

        while (true) {
            while (null !== $event = $this->terminal->events()->next()) {
                if ($event instanceof CharKeyEvent) {
                    if ($event->char === 'q') {
                        break 2;
                    }
                    if ($event->char === '1') {
                        $this->activePage = ActivePage::Home;
                    }
                    if ($event->char === '2') {
                        $this->activePage = ActivePage::Canvas;
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
            Span::fromString(' quit '),
            Span::styled('[1]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString(' home '),
            Span::styled('[2]', Style::default()->fg(AnsiColor::Green)),
            Span::fromString(' canvas'),
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
