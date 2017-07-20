<?php

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\Time;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    public function testTime()
    {
        $time = new Time();

        $this->assertEquals('06:06', $time->timeToText(366000));
        $this->assertEquals('06:06.001', $time->timeToText(366001, true));
        $this->assertEquals('06:06', $time->timeToText(366001, false));
        $this->assertEquals('-06:06', $time->timeToText(-366000));
        $this->assertEquals('-06:06.001', $time->timeToText(-366001, true));
        $this->assertEquals('-06:06', $time->timeToText(-366001, false));

        $this->assertEquals(366000, $time->textToTime('06:06'));
        $this->assertEquals(6000, $time->textToTime('06'));
    }
}
