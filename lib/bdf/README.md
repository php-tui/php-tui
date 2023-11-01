PHP BDF Font Parser
===================

Parser for
[BDF](https://en.wikipedia.org/wiki/Glyph_Bitmap_Distribution_Format) (bitmap) font files.

Inspired by the [embedded systems BDF parser for Rust](https://lib.rs/crates/bdf-parser).

Usage
-----

```
$contents = file_get_contents(__DIR__ . '/../fonts/6x10.bdf');

// ...

$font = (new BdfParser())->parse($contents);
$font->metadata; // ...
$font->glyphs[12]; // ...
```
