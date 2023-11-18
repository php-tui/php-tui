<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Parser;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Span;

class LineParser implements Parser
{
    private const OPEN_TAG_REGEX = '[a-z](?:[^\\\\<>]*+ | \\\\.)*';
    private const CLOSE_TAG_REGEX = '[a-z][^<>]*+';

    /** @var array<Style> */
    private array $styleStack = [];

    public static function new(): self
    {
        return new self();
    }

    /**
     * @return array<Span>
     */
    public function parse(string $input): array
    {
        $regex = sprintf('#<((%s) | /(%s)?)>#ix', self::OPEN_TAG_REGEX, self::CLOSE_TAG_REGEX);
        preg_match_all($regex, $input, $matches, PREG_OFFSET_CAPTURE);

        $spans = [];
        $offset = 0;
        foreach ($matches[0] as $index => $match) {
            $tag = $match[0];
            $pos = $match[1];

            // Skip processing for escaped tags (preceded by a backslash).
            if (0 !== $pos && '\\' == $input[$pos - 1]) {
                continue;
            }

            $textBeforeTag = mb_substr($input, $offset, $pos - $offset);
            if ($textBeforeTag !== '') {
                $spans[] = $this->createSpan($textBeforeTag);
            }

            $offset = $pos + mb_strlen($tag);

            $isOpeningTag = $tag[1] !== '/';
            if ($isOpeningTag) {
                $styleTag = $matches[1][$index][0];
                $this->styleStack[] = $this->createStyleFromTag($styleTag);
            } else {
                array_pop($this->styleStack);
            }
        }

        if ($offset < mb_strlen($input)) {
            $spans[] = $this->createSpan(mb_substr($input, $offset));
        }

        return $spans;
    }

    private function createStyleFromTag(string $tag): Style
    {
        $style = Style::default();
        $attributes = explode(' ', $tag);

        foreach ($attributes as $attribute) {
            $attribute = explode('=', $attribute);
            [$key, $value] = [
                $attribute[0] ?? '',
                $attribute[1] ?? '',
            ];
            if ($key === '' || $value === '') {
                continue;
            }
            $style = $this->patchAttributeToStyle($style, $key, $value);
        }

        // Apply the style of the outermost tag incorporating modifications from the current tag.
        if ($this->styleStack !== []) {
            $outerStyle = end($this->styleStack);
            $style = $outerStyle->patch($style);
        }

        return $style;
    }

    private function patchAttributeToStyle(Style $style, string $key, string $value): Style
    {
        if ($key === 'fg') {
            return $style->fg(AnsiColor::fromName($value));
        }

        if ($key === 'bg') {
            return $style->bg(AnsiColor::fromName($value));
        }

        if ($key === 'options') {
            $options = explode(',', $value);
            foreach ($options as $option) {
                $style->addModifier(Modifier::fromName($option));
            }
        }

        return $style;
    }

    private function createSpan(string $text): Span
    {
        $text = strtr($text, ["\0" => '\\', '\\<' => '<', '\\>' => '>']);

        $style = $this->styleStack === [] ? Style::default() : end($this->styleStack);

        return Span::styled($text, $style);
    }
}
