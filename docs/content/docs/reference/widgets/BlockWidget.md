---
title:  Block 
description: Container for other widgets and can provide a border, title and padding.
---
##  Block 

`PhpTui\Tui\Extension\Core\Widget\BlockWidget`

Container for other widgets and can provide a border, title and padding.
### Example

{{% terminal file="/data/example/docs/widget/blockWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/blockWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **borders** | `int` | Bit mask which determines the border configuration, e.g. Borders::ALL |
| **titles** | `array` | Titles for the block. You can have multiple titles and each title can be positioned in a different place. |
| **borderType** | `PhpTui\Tui\Model\BorderType` | Type of border, e.g. `BorderType::Rounded` |
| **borderStyle** | `PhpTui\Tui\Model\Style` | Style of the border. |
| **style** | `PhpTui\Tui\Model\Style` | Style of the block's inner area. |
| **titleStyle** | `PhpTui\Tui\Model\Style` | Style of the titles. |
| **padding** | `PhpTui\Tui\Extension\Core\Widget\Block\Padding` | Padding to apply to the inner widget. |
| **widget** | `PhpTui\Tui\Model\Widget\|null` | The inner widget. |