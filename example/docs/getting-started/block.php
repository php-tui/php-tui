<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Text\Title;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\BorderType;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->clear();
$display->draw(
    GridWidget::default()
        ->direction(Direction::Horizontal)
        ->constraints(
            Constraint::percentage(50),
            Constraint::percentage(50)
        )
        ->widgets(
            BlockWidget::default()
                ->titles(Title::fromString('Left'))
                ->padding(Padding::all(2))
                ->borders(Borders::ALL)
                ->borderType(BorderType::Rounded)
                ->widget(
                    CanvasWidget::fromIntBounds(-180, 180, -90, 90)
                        ->draw(
                            MapShape::default()->resolution(MapResolution::High)
                        ),
                ),
            BlockWidget::default()
                ->titles(Title::fromString('Right'))
                ->padding(Padding::all(2))
                ->borders(Borders::ALL)
                ->borderType(BorderType::Rounded)
                ->widget(
                    ParagraphWidget::fromText(
                        Text::parse(<<<'EOT'
                            The <fg=green>world</> is the totality of <options=bold>entities</>,
                            the whole of reality, or everything that is.[1] The nature of the
                            world has been <fg=red>conceptualized</> differently in different fields. Some
                            conceptions see the world as unique while others talk of a
                            "plurality of <bg=green>worlds</>".
                            EOT)
                    )
                ),
        )
);
