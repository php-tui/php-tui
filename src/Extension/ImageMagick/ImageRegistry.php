<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick;

use Imagick;
use RuntimeException;

final class ImageRegistry
{
    /**
     * @var array<string,Imagick>
     */
    private array $images = [];

    public function load(string $path): Imagick
    {
        if (isset($this->images[$path])) {
            return $this->images[$path];
        }

        $this->images[$path] = self::loadImage($path);

        return $this->images[$path];
    }

    public static function loadImage(string $path): Imagick
    {
        $image = new Imagick();
        if (!file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Imagefile "%s" does not exist',
                $path
            ));
        }
        if (false === $image->readImage($path)) {
            throw new RuntimeException(sprintf(
                'Could not read file "%s"',
                $path
            ));
        }

        return $image;
    }
}
