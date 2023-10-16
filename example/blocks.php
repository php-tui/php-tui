<?php

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Areas;
use DTL\PhpTui\Model\Backend\DummyBackend;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Exception\TodoException;
use DTL\PhpTui\Model\Layout;
use DTL\PhpTui\Model\Terminal;

require_once __DIR__ . '/../vendor/autoload.php';

$backend = new DummyBackend(128, 33);
$terminal = Terminal::fullscreen($backend);
$terminal->draw(function (Buffer $buffer): void {
    [$titleArea, $layout] = calculate_layout($buffer->area());
    render_title($buffer, $titleArea);

});

function calculate_layout(Area $area): array
{
    $layout = Layout::default()
        ->direction(Direction::Vertical)
        ->constraints([
            Constraint::length(1),
            Constraint::min(10)
        ])
        ->split($area);
    $titleArea = $layout->get(0);
    $mainAreas = array_map(function (Area $area) {
        return Layout::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::percentage(50),
                Constraint::percentage(50),
            ])
            ->split($area)
            ->toArray();
    }, Layout::default()
        ->direction(Direction::Vertical)
        ->constraints(array_map(fn () => Constraint::max(9), array_fill(0, 9, true)))
        ->split($layout->get(1))
        ->toArray());

    return [$titleArea, $mainAreas];
}


function render_title(Buffer $buffer, Area $area): void {
    throw new TodoException('Paragraph!');
}
