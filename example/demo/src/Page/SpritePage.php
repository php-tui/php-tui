<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Points;
use PhpTui\Tui\Widget\Canvas\Shape\Sprite;
use PhpTui\Tui\Widget\Grid;

class SpritePage implements Component
{
    const WIDTH = 100;
    const HEIGHT = 30;

    private Sprite $elephant;

    private int $ticker = 0;

    /**
     * @var list<array{float,float}>
     */
    private array $stars = [];

    /**
     * @var Canvas\Shape\Points
     */
    private Points $points;

    /**
     * @var list<TextShape>
     */
    private array $scroller;

    public function __construct()
    {
        $this->elephant = new Sprite(
            rows: [
                '       █████',
                '   ████████████████████',
                ' █████████████████████████',
                '█████ ███████████████████████',
                '█████████████████████████████████',
                '█████████████████████████████   ██',
                '███  ████████████████████████',
                '███  ███████████████████████ ',
                '███  ███████████████████████ ',
                '███  ████ ████  ████  ██████ ',
            ],
            color: AnsiColor::Blue,
            density: 4,
            alphaChar: ' ',
            xScale: 1,
            yScale: 1,
            position: new FloatPosition(0, 0),
        );
        $this->points = new Points(
            $this->seedStars(),
            AnsiColor::DarkGray,
        );

        $text = implode(
            ' ',
            [
                'PHP-TUI: Building better TUIs!',
                'Once upon a midnight dreary, while I pondered, weak and weary,',
                'Over many a quaint and curious volume of forgotten lore - ',
                'While I nodded, nearly napping, suddenly there came a tapping,',
                'As of some one gently rapping, rapping at my chamber door.',
                '"Tis some visitor," I muttered, "tapping at my chamber door -',
                'Only this and nothing more."',
            ]
        );
        $font = FontRegistry::default()->get('default');
        $this->scroller = array_map(function (string $char, int $offset) use ($font) {
            return new TextShape(
                font: $font,
                text: $char,
                scaleX: 2,
                scaleY: 2,
                color: AnsiColor::Cyan,
                position: FloatPosition::at($offset * 6, 0),
            );
        }, mb_str_split($text), range(0, count(mb_str_split($text)) - 1));
    }

    public function build(): Widget
    {
        $this->tick();

        return Grid::default()
            ->constraints([
                Constraint::length(12),
                Constraint::percentage(70),
            ])
            ->widgets([
                Block::default()
                    ->borders(Borders::ALL)
                    ->borderType(BorderType::Rounded)
                    ->borderStyle(Style::default()->fg(AnsiColor::DarkGray))
                    ->widget(
                        Canvas::default()
                            ->marker(Marker::Block)
                            ->xBounds(AxisBounds::new(0, self::WIDTH))
                            ->yBounds(AxisBounds::new(0, 20))
                            ->paint(function (CanvasContext $context): void {
                                foreach ($this->scroller as $textShape) {
                                    $context->draw($textShape);
                                }
                                $context->saveLayer();
                            })
                    ),
                Canvas::default()
                    ->backgroundColor(AnsiColor::Black)
                    ->marker(Marker::Braille)
                    ->xBounds(AxisBounds::new(0, self::WIDTH))
                    ->yBounds(AxisBounds::new(0, self::HEIGHT))
                    ->paint(function (CanvasContext $context): void {
                        $context->draw($this->points);
                        $context->saveLayer();
                        $context->draw($this->elephant);
                        $context->saveLayer();

                        $elephant2 = clone $this->elephant;
                        $elephant2->xScale = $elephant2->xScale / 2;
                        $elephant2->yScale = $elephant2->yScale / 2;
                        $elephant2->position->change(
                            fn (float $x, float $y) => [
                                $x + 40,
                                10 + 1 * (sin(0.25 * ($this->ticker * 0.5)) * self::HEIGHT / 3)
                            ]
                        );
                        $elephant2->color = RgbColor::fromHsv(100, 100, 100);
                        $context->draw($elephant2);
                        $context->saveLayer();

                        $elephant3 = clone $elephant2;
                        $elephant3->xScale = $elephant3->xScale / 2;
                        $elephant3->yScale = $elephant3->yScale / 2;
                        $elephant3->position->change(
                            fn (float $x, float $y) => [
                                $x + 25,
                                10 + 1 * (sin(0.25 * ($this->ticker * 0.25)) * self::HEIGHT)
                            ]
                        );
                        $elephant3->color = RgbColor::fromHsv($this->ticker  * 32 % 360, 100, 100);
                        $context->draw($elephant3);
                        $context->saveLayer();
                    })
            ]);
    }

    public function handle(Event $event): void
    {
    }

    private function tick(): void
    {
        $this->ticker++;
        $yDeg = sin(0.25 * ($this->ticker)) * self::HEIGHT;
        $xDeg = sin(0.05 * ($this->ticker)) * (self::WIDTH / 20);
        ;
        $this->elephant->position->update(
            5 + 1 * $xDeg,
            10 + 1 * ($yDeg / 3),
        );
        $this->points->coords = $this->stars;
        $this->tickStarfield();
        $this->tickScroller();
    }
    /**
     * @return array<int,array{float,float}>
     */
    private function seedStars(): array
    {
        $points = [];
        for ($i = 0; $i < 100; $i++) {
            $points[] = [(float)rand(0, self::WIDTH, ), (float)rand(0, self::HEIGHT)];
        }
        $this->stars = $points;

        return $points;
    }

    private function tickStarfield(): void
    {
        foreach ($this->stars as $i => [$x, $y]) {
            if ($x > self::WIDTH) {
                $this->stars[$i] = [0, $y];
                continue;
            }
            $this->stars[$i] = [$x + 1, $y];
        }
    }

    private function tickScroller(): void
    {
        foreach ($this->scroller as $i => $textShape) {
            $textShape->position->change(function (float $x, float $y) {
                if ($x < 0) {
                    $x = count($this->scroller) * 6 + 6;
                }
                return [$x -2, $y];
            });
        }
    }
}
