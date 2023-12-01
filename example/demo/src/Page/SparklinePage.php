<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\SparklineWidget;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Widget;

final class SparklinePage implements Component
{
    /**
     * @var list<int<0,max>>
     */
    private array $data1;

    /**
     * @var list<int<0,max>>
     */
    private array $data2;

    /**
     * @var list<int<0,max>>
     */
    private array $data3;

    public function __construct()
    {
        $this->data1 = array_map(fn () => rand(0, 100), range(0, 200));
        $this->data2 = array_map(fn () => rand(0, 100), range(0, 200));
        $this->data3 = array_map(fn () => rand(0, 100), range(0, 200));
    }

    public function build(): Widget
    {
        $this->tickData($this->data1);
        $this->tickData($this->data2);
        $this->tickData($this->data3);

        return GridWidget::default()
            ->constraints(
                Constraint::length(3),
                Constraint::length(3),
                Constraint::min(0)
            )
            ->widgets(
                $this->block('Data 1', SparklineWidget::fromData(...$this->data1)->style(Style::default()->yellow())),
                $this->block('Data 2', SparklineWidget::fromData(...$this->data2)->style(Style::default()->white()->onGreen())),
                $this->block('Data 3', SparklineWidget::fromData(...$this->data3)->style(Style::default()->red())),
            );
    }

    public function handle(Event $event): void
    {
    }

    private function block(string $string, SparklineWidget $sparklineWidget): Widget
    {
        return BlockWidget::default()->titles(Title::fromString($string))->widget($sparklineWidget);
    }

    /**
     * @param list<int<0,max>> $data
     */
    private function tickData(array &$data): void
    {
        array_pop($data);
        array_unshift($data, rand(0, 100));
    }
}
