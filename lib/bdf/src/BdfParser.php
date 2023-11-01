<?php

namespace PhpTui\BDF;

use Closure;

final class BdfParser
{
    public function parse(string $string): BdfFont
    {
        $stream = BdfByteStream::fromString($string);
        $metadata = $this->parseMetadata($stream);
        $properties = $this->skipWhitespace($metadata->rest, $this->parseProperties(...));

        return new BdfFont(
            metadata: $metadata->value,
            properties: $properties->value,
        );
    }

    /**
     * @return BdfResult<BdfMetadata>
     */
    private function parseMetadata(BdfByteStream $stream): BdfResult
    {
        $version = $this->skipWhitespace($stream, $this->metadataVersion(...));
        $name = $this->skipWhitespace($version->rest, $this->metadataName(...));
        $size = $this->skipWhitespace($name->rest, $this->metadataSize(...));
        $boundingBox = $this->skipWhitespace($size->rest, $this->metadataBoundingBox(...));

        [$pointSize, $resolution] = $size->value;

        return BdfResult::ok(new BdfMetadata(
            version: $version->value,
            name: $name->value,
            pointSize: $pointSize,
            resolution: $resolution,
            boundingBox: $boundingBox->value,
        ), $boundingBox->rest);
    }

    /**
     * @return BdfResult<BdfProperties>
     */
    private function parseProperties(BdfByteStream $stream): BdfResult
    {
        return $this->map($stream->delimited(
            /** @phpstan-ignore-next-line */
            $this->statement('STARTPROPERTIES'),
            $this->takeUntil('ENDPROPERTIES'),
            /** @phpstan-ignore-next-line */
            $this->statement('ENDPROPERTIES')
        ), function (BdfResult $result): BdfProperties {
            $result = $this->properties(BdfByteStream::fromString($result->value));
            if (false === $result->isOk()) {
                return BdfResult::failure(new BdfProperties([]), $result->rest);
            }
            return $result;
        })($stream);
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
        if (false === $result->isOk() || !is_numeric($result->value)) {
            return BdfResult::failure(0.0, $stream);
        }

        return BdfResult::ok((float)$result->value, $result->rest);
    }

    /**
     * @return Closure(BdfByteStream): BdfResult<string>
     */
    private function statement(string $string): Closure
    {
        return function (BdfByteStream $stream) use ($string) {
            $result = $stream->takeExact($string);
            if ($result->isOk() === false) {
                return BdfResult::failure('', $stream);
            }
            $result = $this->parseString($result->rest);

            return $result;
        };
    }
    /**
     * @return Closure(BdfByteStream): BdfResult<string>
     */
    private function takeUntil(string $string): Closure
    {
        return function (BdfByteStream $stream) use ($string) {
            return $stream->takeUntil($string);
        };
    }

    /**
     * @template TParse
     * @template TValue
     *
     * @param Closure(BdfByteStream): BdfResult<TParse> $parser
     * @param Closure(BdfResult<TParse>):TValue $map
     * @return Closure(BdfByteStream):TValue
     */
    private function map(Closure $parser, Closure $map): Closure
    {
        return function (BdfByteStream $stream) use ($parser, $map) {
            $result = $parser($stream);
            return $map($result);
        };
    }

    /**
     * @template T
     * @param Closure(BdfByteStream):BdfResult<T> $parser
     * @return Closure(BdfByteStream):BdfResult<list<T>>
     */
    private function many0(Closure $parser): Closure
    {
        return function (BdfByteStream $stream) use ($parser) {
            $result = null;
            $values = [];
            while ($stream->count() > 0) {
                $result = $parser($stream);
                if (false === $result->isOk()) {
                    break;
                }
                $values[] = $result->value;
                $stream = $result->rest;
            }

            return BdfResult::ok($values, $result ? $result->rest : $stream);
        };
    }

    /**
     * @template T
     * @param Closure(BdfByteStream):BdfResult<T> $parser
     * @return BdfResult<BdfProperties>
     */
    private function properties(BdfByteStream $stream): BdfResult
    {
        return $this->skipWhitespace($stream, function (BdfByteStream $stream) {
            $lines = array_map(
                fn (string $line) => explode(' ', $line),
                explode("\n", $stream->toString())
            );
            $properties = [];
            foreach ($lines as $parts) {
                $properties[$parts[0]] = implode(' ', array_slice($parts, 1));
            }

            return BdfResult::ok(new BdfProperties($properties), $stream);
        });
    }
}
