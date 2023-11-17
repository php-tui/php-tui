<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Style;

trait InteractsWithFgColor
{
    public function fg(Color $color): self
    {
        return $this->patchStyle(Style::default()->fg($color));
    }

    public function black(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Black));
    }

    public function red(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Red));
    }

    public function green(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Green));
    }

    public function yellow(): self
    {
        return  $this->patchStyle(Style::default()->fg(AnsiColor::Yellow));
    }

    public function blue(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Blue));
    }

    public function magenta(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Magenta));
    }

    public function cyan(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Cyan));
    }

    public function gray(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::Gray));
    }

    public function darkGray(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::DarkGray));
    }

    public function lightRed(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::LightRed));
    }

    public function lightGreen(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::LightGreen));
    }

    public function lightYellow(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::LightYellow));
    }

    public function lightBlue(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::LightBlue));
    }

    public function lightMagenta(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::LightMagenta));
    }

    public function lightCyan(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::LightCyan));
    }

    public function white(): self
    {
        return $this->patchStyle(Style::default()->fg(AnsiColor::White));
    }
}
