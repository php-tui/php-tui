---
title:  Composite 
description: Render multiple widgets to the same area.
---
##  Composite 

`PhpTui\Tui\Extension\Core\Widget\CompositeWidget`

Render multiple widgets to the same area.


In a grid layout each widget will render to an empty buffer.

This widget enables each widget to overlay widgets on the _same_ buffer
which is useful for showing dialogues, overlaying scrollbars, floating
windows, etc.

### Example

{{% terminal file="/data/example/docs/widget/compositeWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/compositeWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **widgets** | `Widget[] $widgets` |  |