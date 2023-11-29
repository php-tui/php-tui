---
title:  Tabs 
description: A widget that displays a horizontal set of Tabs with a single tab selected.
---
##  Tabs 

`PhpTui\Tui\Extension\Core\Widget\TabsWidget`

A widget that displays a horizontal set of Tabs with a single tab selected.


Each tab title is stored as a `Line` which can be individually styled. The selected tab is set
using `TabsWidget::select($n)` and styled using `TabsWidget::highlightStyle(...)`. The divider can be customized
with `TabsWidget::divider('|')`.

### Example

{{% terminal file="/data/example/docs/widget/tabsWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/tabsWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **titles** | `Line[]` |  |
| **selected** | `int<0, max>` |  |
| **style** | `PhpTui\Tui\Model\Style` |  |
| **highlightStyle** | `PhpTui\Tui\Model\Style` |  |
| **divider** | `PhpTui\Tui\Model\Text\Span` |  |