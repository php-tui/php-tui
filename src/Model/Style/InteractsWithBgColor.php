<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Style;

trait InteractsWithBgColor
{
    public function bg(Color $color): self
    {
        return $this->patchStyle(Style::default()->bg($color));
    }

    public function onBlack(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Black));
    }

    public function onRed(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Red));
    }

    public function onGreen(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Green));
    }

    public function onYellow(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Yellow));
    }

    public function onBlue(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Blue));
    }

    public function onMagenta(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Magenta));
    }

    public function onCyan(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Cyan));
    }

    public function onGray(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::Gray));
    }

    public function onDarkGray(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::DarkGray));
    }

    public function onLightRed(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::LightRed));
    }

    public function onLightGreen(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::LightGreen));
    }

    public function onLightYellow(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::LightYellow));
    }

    public function onLightBlue(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::LightBlue));
    }

    public function onLightMagenta(): self
    {
        return  $this->patchStyle(Style::default()->bg(AnsiColor::LightMagenta));
    }

    public function onLightCyan(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::LightCyan));
    }

    public function onWhite(): self
    {
        return $this->patchStyle(Style::default()->bg(AnsiColor::White));
    }
}
