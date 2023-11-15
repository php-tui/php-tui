<?php

namespace PhpTui\Tui\Model\Style;

use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;

/**
 * @mixin Style
 */
trait ModifierStyler
{
    public function bold(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::BOLD, $active);
    }

    public function dim(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::DIM, $active);
    }

    public function italic(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::ITALIC, $active);
    }

    public function underlined(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::UNDERLINED, $active);
    }

    public function slowBlink(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::SLOWBLINK, $active);
    }

    public function rapidBlink(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::RAPIDBLINK, $active);
    }

    public function reversed(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::REVERSED, $active);
    }

    public function hidden(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::HIDDEN, $active);
    }

    public function crossedOut(bool $active = true): self
    {
        return $this->toggleModifier(Modifier::CROSSEDOUT, $active);
    }

    /**
     * @param int-mask-of<Modifier::*> $modifier
     */
    private function toggleModifier(int $modifier, bool $active): self
    {
        if ($active) {
            return $this->addModifier($modifier);
        }

        return $this->removeModifier($modifier);
    }
}
