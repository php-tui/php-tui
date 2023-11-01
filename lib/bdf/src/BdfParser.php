<?php

namespace PhpTui\BDF;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $stream = BdfByteStream::fromString($string);

        return new BdfFont(
            metadata: $this->parseMetadata($stream),
        );
    }

    private function parseMetadata(BdfByteStream $stream): BdfMetadata
    {
        $version = $this->skipWhitespace($stream, $this->metadataVersion(...));
        $name = $this->skipWhitespace($version->rest, $this->metadataName(...));
        $size = $this->skipWhitespace($name->rest, $this->metadataSize(...));
        $boundingBox = $this->skipWhitespace($size->rest, $this->metadataBoundingBox(...));

        [$pointSize, $resolution] = $size->value;

        return new BdfMetadata(
            version: $version->value,
            name: $name->value,
            pointSize: $pointSize,
            resolution: $resolution,
            boundingBox: $boundingBox->value,
        );
    }
    /**
     * @template T
     * @param callable(BdfByteStream):BdfResult<T> $inner
     * @return BdfResult<T>
     */
    private function skipWhitespace(BdfByteStream $stream, callable $inner): BdfResult
    {
        $result = $stream->skipWhile(fn (string $char) => (bool)preg_match('{\s}', $char));
        $result = $inner($result->rest);
        $result->rest->skipWhile(fn (string $char) => (bool)preg_match('{\s}', $char));
        return $result;
    }

    /**
     * @return BdfResult<float>
     */
    private function metadataVersion(BdfByteStream $stream): BdfResult
    {
        $result = $stream->takeExact('STARTFONT');
        if (false === $result->isOk()) {
            return BdfResult::failure(0.0, $stream);
        }

        return $this->skipWhitespace($result->rest, $this->parseFloat(...));
    }

    /**
     * @return BdfResult<string>
     */
    private function parseString(BdfByteStream $stream): BdfResult
    {
        return $stream->takeWhile(fn (string $char) => $char !== "\n" && $char !== "\r");
    }

    /**
     * @return BdfResult<string>
     */
    private function metadataName(BdfByteStream $stream): BdfResult
    {
        $result = $stream->takeExact('FONT');
        if (false === $result->isOk()) {
            return BdfResult::failure('', $stream);
        }
        return $this->skipWhitespace($result->rest, $this->parseString(...));
    }

    /**
     * @return BdfResult<array{int,BdfSize}>
     */
    private function metadataSize(BdfByteStream $stream): BdfResult
    {
        $result = $stream->takeExact('SIZE');
        $fail = fn () => BdfResult::failure([0,new BdfSize(0, 0)], $stream);
        if (false === $result->isOk()) {
            return $fail();
        }
        $string = $this->skipWhitespace($result->rest, $this->parseString(...));
        if (false === $string->isOk()) {
            return $fail();
        }
        $parts = explode(' ', $string->value);
        if (count($parts) !== 3) {
            return $fail();
        }

        return BdfResult::ok(
            [intval($parts[0]), new BdfSize(intval($parts[1]), intval($parts[2]))],
            $string->rest
        );
    }

    /**
     * @return BdfResult<BdfBoundingBox>
     */
    private function metadataBoundingBox(BdfByteStream $stream): BdfResult
    {
        $fail = fn () => BdfResult::failure(BdfBoundingBox::empty(), $stream);
        $result = $stream->takeExact('FONTBOUNDINGBOX');
        if (false === $result->isOk()) {
            return $fail();
        }
        $string = $this->skipWhitespace($result->rest, $this->parseString(...));

        if (false === $string->isOk()) {
            return $fail();
        }
        $parts = explode(' ', $string->value);
        if (count($parts) !== 4) {
            return $fail();
        }

        return BdfResult::ok(new BdfBoundingBox(
            size: new BdfSize(intval($parts[0]), intval($parts[1])),
            offset: new BdfCoord(intval($parts[2]), intval($parts[3]))
        ), $string->rest);
    }

    /**
     * @return BdfResult<float>
     */
    private function parseFloat(BdfByteStream $stream): BdfResult
    {
        $result = $this->parseString($stream);
        if (null === $result || !is_numeric($result->value)) {
            return BdfResult::failure(0.0, $stream);
        }

        return BdfResult::ok((float)$result->value, $result->rest);
    }
}
