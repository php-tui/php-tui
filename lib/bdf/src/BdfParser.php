<?php

namespace PhpTui\BDF;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $bytes = BdfByteStream::fromString($string);
        $version = $this->skipWhitespace($bytes, $this->metadataVersion(...));
        $name = $this->skipWhitespace($bytes, $this->metadataName(...));
        [$pointSize, $res] = $this->skipWhitespace($bytes, $this->metadataSize(...)) ?: [null, null];
        $boundingBox = $this->skipWhitespace($bytes, $this->metadataBoundingBox(...));

        return new BdfFont(
            metadata: new BdfMetadata(
                version: (float)$version,
                name: $name,
                pointSize: $pointSize,
                resolution: $res,
                boundingBox: $boundingBox,
            )
        );
    }
    /**
     * @template T
     * @param callable(BdfByteStream):T $inner
     * @return T
     */
    private function skipWhitespace(BdfByteStream $stream, callable $inner): mixed
    {
        $stream->skipWhile(fn (string $char) => (bool)preg_match('{\s}', $char));
        $result = $inner($stream);
        $stream->skipWhile(fn (string $char) => (bool)preg_match('{\s}', $char));
        return $result;
    }

    private function metadataVersion(BdfByteStream $stream): ?string
    {
        if (null === $stream->takeExact('STARTFONT')) {
            return null;
        }
        return $this->skipWhitespace($stream, $this->parseString(...));
    }

    private function parseString(BdfByteStream $stream): ?string
    {
        return $stream->takeWhile(fn (string $char) => $char !== "\n" && $char !== "\r");
    }

    private function metadataName(BdfByteStream $stream): ?string
    {
        if (null === $stream->takeExact('FONT')) {
            return null;
        }
        return $this->skipWhitespace($stream, $this->parseString(...));
    }

    /**
     * @return ?array{int, BdfSize}
     */
    private function metadataSize(BdfByteStream $stream): ?array
    {
        if (null === $stream->takeExact('SIZE')) {
            return null;
        }
        $string = $this->skipWhitespace($stream, $this->parseString(...));
        if (null === $string) {
            return null;
        }
        $parts = explode(' ', $string);
        if (count($parts) !== 3) {
            return null;
        }

        return [intval($parts[0]), new BdfSize(intval($parts[1]), intval($parts[2]))];
    }

    private function metadataBoundingBox(BdfByteStream $stream): ?BdfBoundingBox
    {
        if (null === $stream->takeExact('FONTBOUNDINGBOX')) {
            return null;
        }
        $string = $this->skipWhitespace($stream, $this->parseString(...));
        if (null === $string) {
            return null;
        }
        $parts = explode(' ', $string);
        if (count($parts) !== 4) {
            return null;
        }

        return new BdfBoundingBox(
            size: new BdfSize(intval($parts[0]), intval($parts[1])),
            offset: new BdfCoord(intval($parts[2]), intval($parts[3]))
        );
    }
}
