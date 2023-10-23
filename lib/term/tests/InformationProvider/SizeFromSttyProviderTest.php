<?php

namespace PhpTui\Term\Tests\InformationProvider;

use PhpTui\Term\InformationProvider\SizeFromSttyProvider;
use PhpTui\Term\ProcessResult;
use PhpTui\Term\ProcessRunner\ClosureRunner;
use PhpTui\Term\Size;
use PHPUnit\Framework\TestCase;

class SizeFromSttyProviderTest extends TestCase
{
    public function testSizeFromStty(): void
    {
        $runner = ClosureRunner::new(function (array $command) {
            return new ProcessResult(0, <<<'EOT'
                speed 38400 baud; rows 42; columns 140; line = 0;
                intr = ^C; quit = ^\; erase = ^?; kill = ^U; eof = ^D; eol = <undef>; eol2 = <undef>; swtch = <undef>; start = ^Q; stop = ^S; susp = ^Z;
                rprnt = ^R; werase = ^W; lnext = ^V; discard = ^O; min = 1; time = 0;
                -parenb -parodd -cmspar cs8 -hupcl -cstopb cread -clocal -crtscts
                -ignbrk -brkint -ignpar -parmrk -inpck -istrip -inlcr -igncr icrnl -ixon -ixoff -iuclc -ixany -imaxbel iutf8
                opost -olcuc -ocrnl onlcr -onocr -onlret -ofill -ofdel nl0 cr0 tab0 bs0 vt0 ff0
                isig icanon iexten echo echoe echok -echonl -noflsh -xcase -tostop -echoprt echoctl echoke -flusho -extproc
                EOT
            );
        });

        $provider = SizeFromSttyProvider::new($runner);
        $size = $provider->for(Size::class);
        self::assertEquals(new Size(42, 140), $size);
    }
}

