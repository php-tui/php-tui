---
title:  Ansi Color
description: All colors from the ANSI color table are supported (though some names are not exactly the same).
---
##  Ansi Color

`PhpTui\Tui\Color\AnsiColor`

All colors from the ANSI color table are supported (though some names are not exactly the same).


| Color Name     | Color                     | Foreground | Background |
|----------------|---------------------------|------------|------------|
| `black`        | `AnsiColor::Black`        | 30         | 40         |
| `red`          | `AnsiColor::Red`          | 31         | 41         |
| `green`        | `AnsiColor::Green`        | 32         | 42         |
| `yellow`       | `AnsiColor::Yellow`       | 33         | 43         |
| `blue`         | `AnsiColor::Blue`         | 34         | 44         |
| `magenta`      | `AnsiColor::Magenta`      | 35         | 45         |
| `cyan`         | `AnsiColor::Cyan`         | 36         | 46         |
| `gray`*        | `AnsiColor::Gray`         | 37         | 47         |
| `darkgray`*    | `AnsiColor::DarkGray`     | 90         | 100        |
| `lightred`     | `AnsiColor::LightRed`     | 91         | 101        |
| `lightgreen`   | `AnsiColor::LightGreen`   | 92         | 102        |
| `lightyellow`  | `AnsiColor::LightYellow`  | 93         | 103        |
| `lightblue`    | `AnsiColor::LightBlue`    | 94         | 104        |
| `lightmagenta` | `AnsiColor::LightMagenta` | 95         | 105        |
| `lightcyan`    | `AnsiColor::LightCyan`    | 96         | 106        |
| `white`*       | `AnsiColor::White`        | 97         | 107        |

- `gray` is sometimes called `white` - this is not supported as we use `white` for bright white
- `gray` is sometimes called `silver` - this is supported
- `darkgray` is sometimes called `light black` or `bright black` (both are supported)
- `white` is sometimes called `light white` or `bright white` (both are supported)
- we support `bright` and `light` prefixes for all colors
- we support `-` and `_` and ` ` as separators for all colors
- we support both `gray` and `grey` spellings

### Example

{{% terminal file="/data/example/docs/color/ansiColor.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/color/ansiColor.php" language="php" %}}

{{< /details >}}