<?php

namespace PhpTui\Tui\Extension\Bdf;

use PhpTui\BDF\BdfFont;
use PhpTui\BDF\BdfParser;
use RuntimeException;

/**
 * The font registry lazily loads fonts from the filesystem.
 */
final class FontRegistry
{
    /**
     * @var array<string,BdfFont>
     */
    private array $loaded = [];

    /**
     * @param array<string,string> $fontMap
     */
    public function __construct(private BdfParser $parser, private array $fontMap = [])
    {
    }

    public function get(string $name): BdfFont
    {
        if (isset($this->loaded[$name])) {
            return $this->loaded[$name];
        }

        if (!isset($this->fontMap[$name])) {
            throw new RuntimeException(sprintf(
                'Font with name "%s" not registered, known fonts: "%s"',
                $name,
                implode('", "', array_keys($this->fontMap))
            ));
        }

        $contents = file_get_contents($this->fontMap[$name]);
        if (false === $contents) {
            throw new RuntimeException(sprintf(
                'Font file "%s" does not exist or could not be read',
                $this->fontMap[$name]
            ));
        }
        $this->loaded[$name] = $this->parser->parse($contents);

        return $this->loaded[$name];
    }

    public function register(string $name, string $path): self
    {
        if (!file_exists($path)) {
            throw new RuntimeException(
                sprintf('Could not find font file "%s"', $path)
            );
        }

        $map = $this->fontMap;
        $map[$name] = $path;
        return new self($this->parser, $map);
    }

    public static function default(): self
    {
        return new self(new BdfParser(), [
            'default' => __DIR__ . '/../../../lib/bdf/fonts/6x10_ASCII.bdf',
        ]);
    }
}
