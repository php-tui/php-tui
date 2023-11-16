<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;

trait InteractsWithModifier
{
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
