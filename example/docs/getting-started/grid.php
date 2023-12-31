<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Text\Text;
use PhpTui\Tui\Widget\Direction;

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
            CanvasWidget::fromIntBounds(-180, 180, -90, 90)
                ->draw(
                    MapShape::default()->resolution(MapResolution::High)
                ),
            ParagraphWidget::fromText(
                Text::parse(<<<'EOT'
                    The <fg=green>world</> is the totality of <options=bold>entities</>,
                    the whole of reality, or everything that is.[1] The nature of the
                    world has been <fg=red>conceptualized</> differently in different fields. Some
                    conceptions see the world as unique while others talk of a
                    plurality of <bg=green>worlds</>.
                    EOT)
            )
        )
);
