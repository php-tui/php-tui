---
title: ListWidget
description: The List widget allows you to list and highlight items.
---
## ListWidget

The List widget allows you to list and highlight items.
{{% terminal file="/data/example/docs/widget/listWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/listWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **items** | `array` |  |
| **style** | `PhpTui\Tui\Model\Style` |  |
| **startCorner** | `PhpTui\Tui\Model\Corner` |  |
| **highlightStyle** | `PhpTui\Tui\Model\Style` |  |
| **highlightSymbol** | `string` |  |
| **state** | `PhpTui\Tui\Extension\Core\Widget\List\ListState` |  |
| **highlightSpacing** | `PhpTui\Tui\Extension\Core\Widget\List\HighlightSpacing` |  |