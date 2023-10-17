<?php

use DTL\PhpTui\Adapter\Symfony\SymfonyBackend;
use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend\DummyBackend;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use DTL\PhpTui\Model\Modifier;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Terminal;
use DTL\PhpTui\Model\Widget\BorderType;
use DTL\PhpTui\Model\Widget\Borders;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Model\Widget\Line;
use DTL\PhpTui\Model\Widget\Span;
use DTL\PhpTui\Model\Widget\Text;
use DTL\PhpTui\Model\Widget\Title;
use DTL\PhpTui\Model\Widget\VerticalAlignment;
use DTL\PhpTui\Widget\Block;
use DTL\PhpTui\Widget\Paragraph;
use DTL\PhpTui\Widget\Paragraph\Wrap;
use Symfony\Component\Console\Terminal as SymfonyTerminal;

require_once __DIR__ . '/../vendor/autoload.php';

$terminal = new SymfonyTerminal();
$backend = new DummyBackend($terminal->getWidth(), $terminal->getHeight());
$backend = SymfonyBackend::new();
$terminal = Terminal::fullscreen($backend);
$terminal->draw(function (Buffer $buffer): void {
    [$titleArea, $layout] = calculate_layout($buffer->area());
    render_title($buffer, $titleArea);

    $paragraph = placeholder_paragraph();

    render_borders($paragraph, Borders::ALL, $buffer, $layout[0][0]);
    render_borders($paragraph, Borders::NONE, $buffer, $layout[0][1]);
    render_borders($paragraph, Borders::LEFT, $buffer, $layout[1][0]);
    render_borders($paragraph, Borders::RIGHT, $buffer, $layout[1][1]);
    render_borders($paragraph, Borders::TOP, $buffer, $layout[2][0]);
    render_borders($paragraph, Borders::BOTTOM, $buffer, $layout[2][1]);

    render_border_type($paragraph, BorderType::Plain, $buffer, $layout[3][0]);
    render_border_type($paragraph, BorderType::Rounded, $buffer, $layout[3][1]);
    render_border_type($paragraph, BorderType::Double, $buffer, $layout[4][0]);
    render_border_type($paragraph, BorderType::Thick, $buffer, $layout[4][1]);

    // styled block
    $block = Block::default()
        ->borders(Borders::ALL)
        ->style(
            Style::default()->fg(
                AnsiColor::Blue
            )->bg(
                AnsiColor::White
            )->addModifier(
                Modifier::Bold
            )->addModifier(
                Modifier::Italic
            )
        )
        ->title(Title::fromString('Styled block'));
    $paragraph->block($block)->render($layout[5][0], $buffer);

    // styled borders
    $block = Block::default()
        ->borders(Borders::ALL)
        ->borderStyle(
            Style::default()->fg(
                AnsiColor::Blue
            )->bg(
                AnsiColor::White
            )->addModifier(
                Modifier::Bold
            )->addModifier(
                Modifier::Italic
            )
        )
        ->title(Title::fromString('Styled borders'));
    $paragraph->block($block)->render($layout[5][1], $buffer);

    // style title content
    $block = Block::default()
        ->borders(Borders::ALL)
        ->title(Title::fromLine(Line::fromSpans([
            Span::fromString('Styled ')->style(Style::default()->fg(AnsiColor::Blue)),
            Span::fromString('title content')->style(Style::default()->fg(AnsiColor::Green)),
        ])));
    $paragraph->block($block)->render($layout[6][1], $buffer);

    // multiple titles
    $block = Block::default()
        ->borders(Borders::ALL)
        ->title(Title::fromLine(Line::fromSpans([
            Span::fromString('Multiple')->style(Style::default()->fg(AnsiColor::Blue)),
        ])))
        ->title(Title::fromLine(Line::fromSpans([
            Span::fromString('Titles')->style(Style::default()->fg(AnsiColor::Red)),
        ])));
    $paragraph->block($block)->render($layout[7][0], $buffer);

    // multiple title positions
    $block = Block::default()
        ->borders(Borders::ALL)
        ->title(Title::fromString('top left'))
        ->title(Title::fromString('top center')->horizontalAlignmnet(HorizontalAlignment::Center))
        ->title(Title::fromString('top right')->verticalAlignment(VerticalAlignment::Top)->horizontalAlignment(HorizontalAlignment::Right))
        ->title(Title::fromString('bottom left')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Left))
        ->title(Title::fromString('bottom center')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Center))
        ->title(Title::fromString('bottom right')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Right));
    $paragraph->block($block)->render($layout[7][1], $buffer);

    // render padding
    $block = Block::default()
        ->borders(Borders::ALL)
        ->title(Title::fromString('padding not yet supported'));
    $paragraph->block($block)->render($layout[8][0], $buffer);
});

echo $backend->flushed();

/**
 * @return array{Area, list<list<Area>>}
 */
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
        ->constraints(array_map(fn () => Constraint::max(6), array_fill(0, 9, true)))
        ->split($layout->get(1))
        ->toArray());

    return [$titleArea, $mainAreas];
}


function render_title(Buffer $buffer, Area $area): void
{
    Paragraph::new(Text::raw('Block example. Press q to quit'))
        ->style(Style::default()->fg(AnsiColor::DarkGray))
        ->alignment(HorizontalAlignment::Center)
        ->render($area, $buffer);
}

function placeholder_paragraph(): Paragraph
{
    $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    return Paragraph::new(
        Text::styled(
            $text,
            Style::default()->fg(AnsiColor::DarkGray)
        )
    )->wrap(Wrap::trimmed());
}

function render_borders(Paragraph $paragraph, int $borders, Buffer $buffer, Area $area): void
{
    $block = Block::default()
        ->borders($borders)
        ->title(Title::fromString(sprintf('Borders::%s', Borders::toString($borders))));
    $paragraph->block($block)->render($area, $buffer);
}
function render_border_type(Paragraph $paragraph, BorderType $borderType, Buffer $buffer, Area $area): void
{
    Block::default()
        ->borders(Borders::ALL)
        ->borderType($borderType)
        ->title(Title::fromString(sprintf('BordersType::%s', $borderType->name)))
        ->render($area, $buffer);
}
