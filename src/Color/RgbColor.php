<?php

declare(strict_types=1);

namespace PhpTui\Tui\Color;

use InvalidArgumentException;
use OutOfBoundsException;
use PhpTui\Tui\Position\FractionalPosition;
use Stringable;

/**
 * Represents 24 bit (true) color.
 *
 * You can create this object from RGB, HSV or Hexidecimal values using static
 * constructors.
 */
final class RgbColor implements Color, Stringable
{
    private function __construct(public int $r, public int $g, public int $b)
    {
        self::assertRange('red', 0, 255, $r);
        self::assertRange('green', 0, 255, $r);
        self::assertRange('blue', 0, 255, $r);
    }

    public function __toString(): string
    {
        return $this->debugName();
    }

    public static function fromRgb(int $r, int $g, int $b): self
    {
        return new self($r, $g, $b);
    }

    public static function fromHsv(int $hue, int $saturation, int $lightness): self
    {
        self::assertRange('hue', 0, 360, $hue);
        self::assertRange('saturation', 0, 100, $saturation);
        self::assertRange('lightness', 0, 100, $lightness);
        // stolen from: https://gist.github.com/vkbo/2323023
        /*
         **  Converts HSV to RGB values
         ** –––––––––––––––––––––––––––––––––––––––––––––––––––––
         **  Reference: http://en.wikipedia.org/wiki/HSL_and_HSV
         **  Purpose:   Useful for generating colours with
         **             same hue-value for web designs.
         **  Input:     Hue        (H) Integer 0-360
         **             Saturation (S) Integer 0-100
         **             Lightness  (V) Integer 0-100
         **  Output:    String "R,G,B"
         **             Suitable for CSS function RGB().
         */

        if ($hue < 0) {
            $hue = 0;
        }   // Hue:
        if ($hue > 360) {
            $hue = 360;
        } //   0-360
        if ($saturation < 0) {
            $saturation = 0;
        }   // Saturation:
        if ($saturation > 100) {
            $saturation = 100;
        } //   0-100
        if ($lightness < 0) {
            $lightness = 0;
        }   // Lightness:
        if ($lightness > 100) {
            $lightness = 100;
        } //   0-100

        $dS = $saturation / 100.0; // Saturation: 0.0-1.0
        $dV = $lightness / 100.0; // Lightness:  0.0-1.0
        $dC = $dV * $dS;   // Chroma:     0.0-1.0
        $dH = $hue / 60.0;  // H-Prime:    0.0-6.0
        $dT = $dH;       // Temp variable

        while ($dT >= 2.0) {
            $dT -= 2.0;
        } // php modulus does not work with float
        $dX = $dC * (1 - abs($dT - 1));     // as used in the Wikipedia link

        switch ((int) floor($dH)) {
            case 0:
                $dR = $dC;
                $dG = $dX;
                $dB = 0.0;

                break;
            case 1:
                $dR = $dX;
                $dG = $dC;
                $dB = 0.0;

                break;
            case 2:
                $dR = 0.0;
                $dG = $dC;
                $dB = $dX;

                break;
            case 3:
                $dR = 0.0;
                $dG = $dX;
                $dB = $dC;

                break;
            case 4:
                $dR = $dX;
                $dG = 0.0;
                $dB = $dC;

                break;
            case 5:
                $dR = $dC;
                $dG = 0.0;
                $dB = $dX;

                break;
            default:
                $dR = 0.0;
                $dG = 0.0;
                $dB = 0.0;

                break;
        }

        $dM = $dV - $dC;
        $dR += $dM;
        $dG += $dM;
        $dB += $dM;
        $dR *= 255;
        $dG *= 255;
        $dB *= 255;

        return new self(
            (int) (round($dR)),
            (int) (round($dG)),
            (int) (round($dB))
        );
    }

    public static function fromHex(string $hex): self
    {
        $hex = ltrim($hex, '#');

        // Expand shorthand notation
        if (strlen($hex) === 3) {
            /** @var string $hex */
            $hex = preg_replace('/(.)(.)(.)/', '$1$1$2$2$3$3', $hex);
        }

        if (!preg_match('/^[a-fA-F0-9]{6}$/', $hex)) {
            throw new InvalidArgumentException(sprintf('Invalid hex color: %s', $hex));
        }

        $int = hexdec($hex);

        return new self(
            r: 0xFF & ($int >> 0x10),
            g: 0xFF & ($int >> 0x8),
            b: 0xFF & $int,
        );
    }

    public function debugName(): string
    {
        return sprintf('RGB(%d, %d, %d)', $this->r, $this->g, $this->b);
    }

    public function toHex(): string
    {
        return sprintf('#%02x%02x%02x', $this->r, $this->g, $this->b);
    }

    public function at(FractionalPosition $position): RgbColor
    {
        return $this;
    }

    private static function assertRange(string $context, int $min, int $max, int $value): void
    {
        if ($value >= $min && $value <= $max) {
            return;
        }

        throw new OutOfBoundsException(sprintf(
            '%s must be in range %d-%d got %d',
            $context,
            $min,
            $max,
            $value
        ));
    }
}
