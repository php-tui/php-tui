<?php

namespace PhpTui\BDF;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $bytes = BdfByteStream::fromString($string);
        $result = $this->skipWhitespace($bytes, $this->metadataVersion(...));
        $result = $this->skipWhitespace($result->rest, $this->metadataName(...));
        $result = $this->skipWhitespace($result->rest, $this->metadataSize(...)) ?: [null, null];
        $result = $this->skipWhitespace($result->rest, $this->metadataBoundingBox(...));

        return new BdfFont(
            metadata: new BdfMetadata(
                version: $version->value,
                name: $name,
                pointSize: $pointSize,
                resolution: $res,
                boundingBox: $boundingBox,
            )
        );
    }
    /**
     * @template T
     * @param callable(BdfByteStream):?BdfResult<T> $inner
     * @return BdfResult<T>
     */
    private function skipWhitespace(BdfByteStream $stream, callable $inner): ?BdfResult
    {
        $result = $stream->skipWhile(fn (string $char) => (bool)preg_match('{\s}', $char));
        $result = $inner($result->rest);
        $result->rest->skipWhile(fn (string $char) => (bool)preg_match('{\s}', $char));
        return $result;
    }

    /**
     * @return ?BdfResult<float>
     */
    private function metadataVersion(BdfByteStream $stream): ?BdfResult
    {
        if (null === $result = $stream->takeExact('STARTFONT')) {
            return null;
        }
        return $this->skipWhitespace($result->rest, $this->parseFloat(...));
    }

    /**
     * @return ?BdfResult<string>
     */
    private function parseString(BdfByteStream $stream): ?BdfResult
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

    /**
     * @return ?BdfResult<float>
     */
    private function parseFloat(BdfByteStream $stream): ?BdfResult
    {
        $result = $this->parseString($stream);
        if (null === $result || !is_numeric($result->value)) {
            return null;
        }

        return new BdfResult((float)$result->value, $result->rest);
    }
}
