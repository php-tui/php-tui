<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Points;
use PhpTui\Tui\Widget\Canvas\Shape\Sprite;

class NyanPage implements Component
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
            density: 100,
            alphaChar: ' ',
            xScale: 1,
            yScale: 1,
            position: new FloatPosition(0, 0),
        );
        $this->points = new Points(
            $this->seedStars(),
            AnsiColor::DarkGray,
        );

    }

    public function build(): Widget
    {
        $this->tick();

        return Canvas::default()
            ->backgroundColor(AnsiColor::Black)
            ->marker(Marker::Braille)
            ->xBounds(AxisBounds::new(0, self::WIDTH))
            ->yBounds(AxisBounds::new(0, self::HEIGHT))
            ->paint(function (CanvasContext $context): void {
                $context->draw($this->points);
                $context->saveLayer();
                $context->draw($this->elephant);
                $context->saveLayer();
            });
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
    }
    /**
     * @return array<int,array{float,float}>
     */
    private function seedStars(): array
    {
        $points = [];
        for ($i = 0; $i < 100; $i++) {
            $points[] = [(float)rand(0, self::WIDTH,), (float)rand(0, self::HEIGHT)];
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
}
