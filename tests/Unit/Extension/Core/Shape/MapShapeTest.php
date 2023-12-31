<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;

final class MapShapeTest extends ShapeTestCase
{
    /**
     * @param array<int,string> $expected
     * @dataProvider provideMap
     */
    public function testMap(MapResolution $resolution, Marker $marker, array $expected): void
    {
        $canvas = CanvasWidget::default()
            ->marker($marker)
            ->xBounds(AxisBounds::new(-180, 180))
            ->yBounds(AxisBounds::new(-90, 90))
            ->paint(static function (CanvasContext $context) use ($resolution): void {
                $context->draw(MapShape::default()->resolution($resolution));
            });
        $area = Area::fromDimensions(80, 40);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<string,array{MapResolution,Marker,array<int,string>}>
     */
    public static function provideMap(): Generator
    {
        yield 'low' => [
            MapResolution::Low,
            Marker::Dot,
            [
            '                                                                                ',
            '                   ••••••• •• •• •• •                                           ',
            '            ••••••••••••••       •••      ••••  •••  ••    ••••                 ',
            '            ••••••••••••••••     ••                ••• ••••••• •• •• •••        ',
            '• • •• •••••• •••••••••••• ••   •••  •    •••••  •••••••••          ••  • • • • ',
            '•••••       ••••  •••••••• •• ••  •••    •••• ••••    •• •                    • ',
            '   ••••••••  ••••••• •••••  •••       ••••••••                        • •••••   ',
            '  •• ••   ••    •••••••  ••          ••• ••••                        ••    •    ',
            '•••       •••    •••••• ••••         ••••                             •• •   •• ',
            '            •      •••••••••          ••  •   ••• • •• ••            ••         ',
            '            • •     ••••             •• ••••••••• •••   •         • • ••        ',
            '            •         •               ••••• ••••  ••             ••••••         ',
            '             •      ••               •   • •• •                  •••••          ',
            '              ••  •• •              •         ••  ••              •             ',
            '    ••        •••   •••            •           •  •••••    •   •••              ',
            '     •           •••• •••                       •   •  •    •  • ••             ',
            '                  •••• •           •            •• •     •  ••   ••             ',
            '                     ••• ••         •           • •     ••   ••• •••            ',
            '                      •    •        • •• •              •   •   •  •            ',
            '                   •  •     •            •    • •            ••• •  •           ',
            '                     •        •           •   •              •• •   • •         ',
            '                               •                •              ••   ••• •       ',
            ' •                    •       •           •     • •                • •          ',
            '                        •                 •    • ••               •  • •   •  • ',
            '                              •                •                •       •       ',
            '                       •    •                 •  •              •        •      ',
            '                       •   ••              • •                  • • ••       •  ',
            '                       •  •                •                         ••••    •• ',
            '                       • •                                             ••   ••• ',
            '                       ••                                                   •   ',
            '                       •• •                                                     ',
            '                       ••                                                       ',
            '                                                                                ',
            '                        •••                        •      •••• • • •• •         ',
            '                       ••••           •••••• •••••• ••••••             • •••    ',
            '         •• •••••• ••••• ••      • ••• •                                   ••   ',
            '•  •••••             ••  •• ••••••                                         • •• ',
            '•    •                 •   •  •                                             • • ',
            '       •                                                                        ',
            '                                                                                ',
            ]
        ];
        yield 'high' => [
            MapResolution::High,
            Marker::Braille,
            [
            '                                                                                ',
            '                  ⢀⣠⠤⠤⠤⠔⢤⣤⡄⠤⡠⣄⠢⠂⢢⠰⣠⡄⣀⡀                      ⣀                   ',
            '            ⢀⣀⡤⣦⠲⢶⣿⣮⣿⡉⣰⢶⢏⡂        ⢀⣟⠁     ⢺⣻⢿⠏   ⠈⠉⠁ ⢀⣀    ⠈⠓⢳⣢⣂⡀               ',
            '            ⡞⣳⣿⣻⡧⣷⣿⣿⢿⢿⣧⡀⠉⠉⠙⢆      ⣰⠇               ⣠⠞⠃⢉⣄⣀⣠⠴⠊⠉⠁ ⠐⠾⠤⢤⠤⡄⠐⣻⠜⢓⠂      ',
            '⢍ ⢀⡴⠊⠙⠓⠒⠒⠤⠖⠺⠿⠽⣷⣬⢬⣾⣷⢻⣷⢲⢲⣍⠱⡀ ⠹⡗   ⢀⢐⠟        ⡔⠒⠉⠲⠤⢀⢄⡀⢩⣣⠦⢷⢼⡏⠈          ⠉⠉⠉ ⠈⠈⠉⠖⠤⠆⠒⠭',
            '⠶⢽⡲⣽⡆             ⠈⣠⣽⣯⡼⢯⣘⡯⠃⠘⡆ ⢰⠒⠁ ⢾⣚⠟    ⢀⠆ ⣔⠆ ⢷⠾⠋⠁    ⠙⠁                     ⠠⡤',
            '  ⠠⢧⣄⣀⡶⠦⠤⡀        ⢰⡁ ⠉⡻⠙⣎⡥  ⠘⠲⠇       ⢀⡀⠨⣁⡄⣸⢫⡤⠄                        ⣀⢠⣤⠊⣼⠅⠖⠋⠁',
            '   ⣠⠾⠛⠁  ⠈⣱        ⠋⠦⢤⡼ ⠈⠈⠦⡀         ⢀⣿⣇ ⢹⣷⣂⡞⠃                       ⢀⣂⡀  ⠏⣜    ',
            '          ⠙⣷⡄        ⠘⠆ ⢀⣀⡠⣗         ⠘⣻⣽⡟⠉⠈                           ⢹⡇  ⠟⠁    ',
            '           ⠈⡟           ⢎⣻⡿⠾⠇         ⠘⠇  ⣀⡀  ⣤⣤⡆ ⡠⡦                 ⢀⠎⡏        ',
            '            ⡇          ⣀⠏⠋           ⢸⠒⢃⡖⢻⢟⣷⣄⣰⣡⠥⣱ ⢏⣧              ⣀ ⡴⠚⢰⠟        ',
            '            ⢳         ⢸⠃             ⠸⣄⣼⣠⢼⡴⡟⢿⢿⣀⣄  ⠸⡹             ⠘⡯⢿⡇⡠⢼⠁        ',
            '             ⢳⣀      ⢀⠞⠁             ⢠⠋⠁ ⠐⠧⡄⣬⣉⣈⡽                  ⢧⠘⢽⠟⠉         ',
            '              ⣿⣄  ⡴⠚⠛⣿⣀             ⢠⠖     ⠈⠁ ⠹⣧  ⢾⣄⡀             ⡼ ⠈           ',
            '    ⣀         ⠘⣿⡄ ⡇  ⣘⣻             ⡏          ⢻⡄ ⠘⠿⢿⠒⠲⡀   ⢀⡀   ⢀⡰⣗             ',
            '    ⠉⠷          ⢫⡀⢧⡼⡟⠉⣛⣳⣦⡀         ⠈⡇          ⠸⣱  ⢀⡼  ⢺  ⡸⠉⢇  ⣾⡏ ⣁             ',
            '                 ⠉⠒⢆⡓⡆             ⠠⡃           ⢳⣇⡠⠏   ⠐⡄⡞  ⠘⣇⡀⢱  ⣾⡀            ',
            '                    ⢹⣇⣀⣾⡷⠤⡆         ⢣            ⠯⢺⠇    ⢣⣅   ⣽⢱⡔ ⢠⢿⣗            ',
            '                     ⠙⢱   ⠘⠦⡄       ⠈⢦⡠⣠⢶⣀        ⡜     ⠈⠿  ⢠⣽⢆ ⢀⣼⡜⠿            ',
            '                     ⢀⡞     ⢱⡀           ⢸       ⡔⠁          ⢻⢿⢰⠏⢸⣤⣴⣆           ',
            '                     ⢘⠆      ⠙⠢⢄         ⠸⡀     ⡸⠁           ⠈⣞⡎⠥⡟⣿⠠⠿⣷⠒⢤⢀⣆      ',
            '                     ⠘⠆        ⢈⠂         ⢳     ⡇             ⠈⠳⠶⣤⣭⣠ ⠋⢧⡬⣟⠉⠷⡄    ',
            '                      ⢨        ⡜          ⢸     ⠸ ⣠               ⠁⢁⣰⢶ ⡇⠉⠁ ⠛    ',
            '⠆                     ⠈⢱⡀      ⡆          ⡇    ⢀⡜⡴⢹               ⢰⠏⠁⠘⢶⠹⡀   ⠸ ⢠⡶',
            '                        ⠅     ⣸           ⢸    ⢫ ⡞⡊             ⢠⠔⠋     ⢳⡀ ⠐⣦   ',
            '                        ⡅    ⡏            ⠈⡆  ⢠⠎ ⠳⠃             ⢸        ⢳      ',
            '                       ⠨    ⡸⠁             ⢱  ⡸                 ⠈⡇ ⢀⣀⡀   ⢸      ',
            '                       ⠸  ⠐⡶⠁              ⠘⠖⠚                   ⠣⠒⠋ ⠱⣇ ⢀⠇   ⠰⡄ ',
            '                       ⠽ ⣰⡖⠁                                          ⠘⢚⡊    ⢀⣿⠇',
            '                       ⡯⢀⡟                                             ⠘⠏   ⢠⢾⠃ ',
            '                       ⠇⢨⠆                            ⢠⡄                    ⠈⠁  ',
            '                       ⢧⣷⡀⠚                                                     ',
            '                        ⠉⠁                                                      ',
            '                          ⢀⡀                                                    ',
            '                        ⢠⡾⠋                      ⣀⡠⠖⢦⣀⣀  ⣀⠤⠦⢤⠤⠶⠤⠖⠦⠤⠤⠤⠴⠤⢤⣄       ',
            '                ⢀⣤⣀ ⡀  ⣼⣻⠙⡆         ⢀⡤⠤⠤⠴⠒⠖⠒⠒⠒⠚⠉⠋⠁    ⢰⡳⠊⠁              ⠈⠉⠉⠒⠤⣤  ',
            '    ⢀⣀⣀⡴⠖⠒⠒⠚⠛⠛⠛⠒⠚⠳⠉⠉⠉⠉⢉⣉⡥⠔⠃     ⢀⣠⠤⠴⠃                                      ⢠⠞⠁  ',
            '   ⠘⠛⣓⣒⠆              ⠸⠥⣀⣤⡦⠠⣞⣭⣇⣘⠿⠆                                         ⣖⠛   ',
            '⠶⠔⠲⠤⠠⠜⢗⠤⠄                 ⠘⠉  ⠁                                            ⠈⠉⠒⠔⠤',
            '                                                                                ',
            ]
        ];
    }
}
