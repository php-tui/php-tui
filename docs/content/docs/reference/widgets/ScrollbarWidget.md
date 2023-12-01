---
title:  Scrollbar 
description: A widget to display a scrollbar
---
##  Scrollbar 

`PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget`

A widget to display a scrollbar


The following components of the scrollbar are customizable in symbol and style.

```text
<--▮------->
^  ^   ^   ^
│  │   │   └ end
│  │   └──── track
│  └──────── thumb
└─────────── begin
```

### Example

{{% terminal file="/data/example/docs/widget/scrollbarWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/scrollbarWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **orientation** | `PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation` | If this is a horizontal or a vertical scrollbar |
| **thumbStyle** | `PhpTui\Tui\Style\Style` | Style for the thumb |
| **thumbSymbol** | `string` | Symbol for the thumb |
| **trackStyle** | `PhpTui\Tui\Style\Style` | Style for the track |
| **trackSymbol** | `string\|null` | Symbol for the track |
| **beginSymbol** | `string\|null` | Beginning symbol, e.g. 👈 |
| **beginStyle** | `PhpTui\Tui\Style\Style` | Style for the beginning symbol |
| **endSymbol** | `string\|null` | Ending symbol, e.g. 👉 |
| **endStyle** | `PhpTui\Tui\Style\Style` | Style for the ending symbol |
| **state** | `PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState` | The state |