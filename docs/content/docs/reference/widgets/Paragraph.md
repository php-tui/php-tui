---
title: Paragraph
description: This widget has the ability to show and wrap text.
---
## Paragraph

This widget has the ability to show and wrap text.
{{% terminal file="/data/example/docs/widget/paragraph.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/paragraph.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **style** | `PhpTui\Tui\Model\Style` |  |
| **wrap** | `PhpTui\Tui\Extension\Core\Widget\Paragraph\Wrap\|null` |  |
| **text** | `PhpTui\Tui\Model\Widget\Text` |  |
| **scroll** | `array` |  |
| **alignment** | `PhpTui\Tui\Model\Widget\HorizontalAlignment` |  |