<?php

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;

/**
 * @mixin Style
 */
trait FgColorStyler
{
    public function black(): self
    {
        return $this->fg(AnsiColor::Black);
    }

    public function red(): self
    {
        return $this->fg(AnsiColor::Red);
    }

    public function green(): self
    {
        return $this->fg(AnsiColor::Green);
    }

    public function yellow(): self
    {
        return $this->fg(AnsiColor::Yellow);
    }

    public function blue(): self
    {
        return $this->fg(AnsiColor::Blue);
    }

    public function magenta(): self
    {
        return $this->fg(AnsiColor::Magenta);
    }

    public function cyan(): self
    {
        return $this->fg(AnsiColor::Cyan);
    }

    public function gray(): self
    {
        return $this->fg(AnsiColor::Gray);
    }

    public function darkGray(): self
    {
        return $this->fg(AnsiColor::DarkGray);
    }

    public function lightRed(): self
    {
        return $this->fg(AnsiColor::LightRed);
    }

    public function lightGreen(): self
    {
        return $this->fg(AnsiColor::LightGreen);
    }

    public function lightYellow(): self
    {
        return $this->fg(AnsiColor::LightYellow);
    }

    public function lightBlue(): self
    {
        return $this->fg(AnsiColor::LightBlue);
    }

    public function lightMagenta(): self
    {
        return $this->fg(AnsiColor::LightMagenta);
    }

    public function lightCyan(): self
    {
        return $this->fg(AnsiColor::LightCyan);
    }

    public function white(): self
    {
        return $this->fg(AnsiColor::White);
    }
}
