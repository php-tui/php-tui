<?php

namespace PhpTui\Term\Tests\Painter;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\Actions;
use PhpTui\Term\Painter\HtmlCanvasPainter;
use PhpTui\Term\Painter\HtmlStylePainter;

class HtmlCanvasPainterTest extends TestCase
{
    public function testPaint(): void
    {
        $painter = HtmlCanvasPainter::default(2, 5);
        $painter->paint([
            Actions::printString('Hell'),
            Actions::moveCursor(2, 5),
            Actions::printString('Worl'),
        ]);
        self::assertNormalizedEquals(
            <<<'EOT'
                <canvas id="term_6553463265112" width=16 height=40></canvas>
                <script>
                const canvas = document.getElementById("term_6553463265112");
                const ctx = canvas.getContext("2d");
                ctx.font = "12px monospace";
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(0,0,16,40);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(0,0,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("H",0,8);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(8,0,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("e",8,8);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(0,8,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("l",0,16);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(8,8,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("l",8,16);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(0,24,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("W",0,32);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(8,24,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("o",8,32);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(0,32,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("r",0,40);
                ctx.fillStyle = "rgb(10,10,10)";
                ctx.fillRect(8,32,8,8);
                ctx.fillStyle = "rgb(255,255,255)";
                ctx.fillText("l",8,40);
                </script>
                EOT,
            $painter->toString()
        );
    }

    private static function assertNormalizedEquals(string $string, string $string2): void
    {
        self::assertEquals(self::normalize($string), self::normalize($string2));
    }

    private static function normalize(string $string): string
    {
        $normalized = preg_replace('{canvas id=".*?"}', 'canvas id=***', $string);
        $normalized = preg_replace('{getElementById\(".*?"\)}', 'getElementById(***)', (string)$normalized);
        return (string)$normalized;
    }
}
