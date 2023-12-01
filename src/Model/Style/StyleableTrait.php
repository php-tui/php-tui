<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Color\Color;

trait StyleableTrait
{
    public function fg(Color $color): self
    {
        return $this->patchStyle(Style::default()->fg($color));
    }

    public function bg(Color $color): self
    {
        return $this->patchStyle(Style::default()->bg($color));
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

    public function bold(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::BOLD, $enable);
    }

    public function dim(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::DIM, $enable);
    }

    public function italic(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::ITALIC, $enable);
    }

    public function underlined(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::UNDERLINED, $enable);
    }

    public function slowBlink(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::SLOWBLINK, $enable);
    }

    public function rapidBlink(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::RAPIDBLINK, $enable);
    }

    public function reversed(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::REVERSED, $enable);
    }

    public function hidden(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::HIDDEN, $enable);
    }

    public function crossedOut(bool $enable = true): self
    {
        return $this->toggleModifier(Modifier::CROSSEDOUT, $enable);
    }

    /**
     * @param int-mask-of<Modifier::*> $modifier
     */
    private function toggleModifier(int $modifier, bool $enable): self
    {
        if ($enable) {
            return $this->patchStyle(Style::default()->addModifier($modifier));
        }

        return $this->patchStyle(Style::default()->removeModifier($modifier));
    }
}
