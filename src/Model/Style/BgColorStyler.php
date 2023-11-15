<?php

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;

/**
 * @mixin Style
 */
trait BgColorStyler
{
    public function onBlack(): self
    {
        return $this->bg(AnsiColor::Black);
    }

    public function onRed(): self
    {
        return $this->bg(AnsiColor::Red);
    }

    public function onGreen(): self
    {
        return $this->bg(AnsiColor::Green);
    }

    public function onYellow(): self
    {
        return $this->bg(AnsiColor::Yellow);
    }

    public function onBlue(): self
    {
        return $this->bg(AnsiColor::Blue);
    }

    public function onMagenta(): self
    {
        return $this->bg(AnsiColor::Magenta);
    }

    public function onCyan(): self
    {
        return $this->bg(AnsiColor::Cyan);
    }

    public function onGray(): self
    {
        return $this->bg(AnsiColor::Gray);
    }

    public function onDarkGray(): self
    {
        return $this->bg(AnsiColor::DarkGray);
    }

    public function onLightRed(): self
    {
        return $this->bg(AnsiColor::LightRed);
    }

    public function onLightGreen(): self
    {
        return $this->bg(AnsiColor::LightGreen);
    }

    public function onLightYellow(): self
    {
        return $this->bg(AnsiColor::LightYellow);
    }

    public function onLightBlue(): self
    {
        return $this->bg(AnsiColor::LightBlue);
    }

    public function onLightMagenta(): self
    {
        return $this->bg(AnsiColor::LightMagenta);
    }

    public function onLightCyan(): self
    {
        return $this->bg(AnsiColor::LightCyan);
    }

    public function onWhite(): self
    {
        return $this->bg(AnsiColor::White);
    }
}
