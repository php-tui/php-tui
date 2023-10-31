<?php

namespace PhpTui\BDF;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $bytes = BdfByteStream::fromString($string);
        $version = $this->skipWhitespace($bytes, $this->metadataVersion(...));

        return new BdfFont(
            metadata: new BdfMetadata(
                version: (float)$version,
                name: '',
                pointSize: 0,
                resolution: new BdfSize(width: 0, height: 0),
                boundingBox: new BdfBoundingBox(
                    size: new BdfSize(width: 0, height: 0),
                    offset: new BdfCoord(x: 0, y: 0)
                )
            )
        );
    }
    /**
     * @param callable(BdfByteStream): mixed $inner
     */
    private function skipWhitespace(BdfByteStream $stream, callable $inner): ?string
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
}
