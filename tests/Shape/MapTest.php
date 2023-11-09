<?php

namespace PhpTui\Tui\Tests\Shape;

use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Shape\Map;
use PhpTui\Tui\Shape\MapResolution;
use Generator;

class MapTest extends ShapeTestCase
{
    /**
     * @param array<int,string> $expected
     * @dataProvider provideMap
     */
    public function testMap(MapResolution $resolution, Marker $marker, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker($marker)
            ->xBounds(AxisBounds::new(-180, 180))
            ->yBounds(AxisBounds::new(-90, 90))
            ->paint(function (CanvasContext $context) use ($resolution): void {
                $context->draw(Map::default()->resolution($resolution));
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
