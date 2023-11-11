<?php

namespace PhpTui\Tui\Adapter\ImageMagick;

use Closure;
use Imagick;

final class ImageRegistry 
{
    /**
     * @var array<string,Imagick>
     */
    private array $images = [];

    /**
     * @param Closure(string):Imagick $loader
     */
    public function load(string $path, Closure $loader): Imagick
    {
        if (isset($this->images[$path])) {
            return $this->images[$path];
        }

        $this->images[$path] = $loader($path);

        return $this->images[$path];
    }
}
