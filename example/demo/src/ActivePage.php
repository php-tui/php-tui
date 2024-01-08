<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo;

use RuntimeException;

enum ActivePage
{
    case Events;
    case Canvas;
    case Chart;
    case List;
    case Table;
    case Blocks;
    case Sprite;
    case Colors;
    case Images;
    case CanvasScaling;
    case Gauge;
    case BarChart;
    case Sparkline;
    case Window;

    public function navItem(): NavItem
    {
        return match ($this) {
            ActivePage::Events => new NavItem('1', 'events'),
            ActivePage::Canvas => new NavItem('2', 'canvas'),
            ActivePage::Chart => new NavItem('3', 'chart'),
            ActivePage::List => new NavItem('4', 'list'),
            ActivePage::Table => new NavItem('5', 'table'),
            ActivePage::Blocks => new NavItem('6', 'blocks'),
            ActivePage::Sprite => new NavItem('7', 'sprite'),
            ActivePage::Colors => new NavItem('8', 'colors'),
            ActivePage::Images => new NavItem('9', 'images'),
            ActivePage::Gauge => new NavItem('!', 'guage'),
            ActivePage::BarChart => new NavItem('"', 'barchart'),
            ActivePage::CanvasScaling => new NavItem('0', 'scaling'),
            ActivePage::Sparkline => new NavItem('£', 'sparkline'),
            ActivePage::Window => new NavItem('£', 'window'),
        };
    }

    public function next(): self
    {
        foreach (self::cases() as $i => $case) {
            if ($case === $this) {
                return self::cases()[($i + 1) % count(self::cases())];
            }
        }

        throw new RuntimeException('should not happen!');
    }
    public function previous(): self
    {
        $cases = self::cases();
        foreach (self::cases() as $i => $case) {
            if ($case === $this) {
                return $cases[($i - 1) < 0 ? count($cases) - 1 : $i - 1];
            }
        }

        throw new RuntimeException('should not happen!');
    }

    public function index(): int
    {
        $search = array_search($this, self::cases(), true);

        return $search ? $search : 0;
    }
}
