---
title:  Paragraph 
description: This widget has the ability to show and wrap text.
---
##  Paragraph 

`PhpTui\Tui\Extension\Core\Widget\ParagraphWidget`

This widget has the ability to show and wrap text.
### Example

{{% terminal file="/data/example/docs/widget/paragraphWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/paragraphWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **style** | `PhpTui\Tui\Style\Style` |  |
| **wrap** | `PhpTui\Tui\Extension\Core\Widget\Paragraph\Wrap\|null` |  |
| **text** | `PhpTui\Tui\Text\Text` |  |
| **scroll** | `array` |  |
| **alignment** | `PhpTui\Tui\Widget\HorizontalAlignment` |  |