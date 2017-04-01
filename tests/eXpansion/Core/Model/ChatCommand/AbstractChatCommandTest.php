<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 17:41
 */

namespace Tests\eXpansion\Core\Model\ChatCommand;

use eXpansion\Core\Model\ChatCommand\AbstractChatCommand;
use Tests\eXpansion\Core\TestHelpers\Model\TestChatCommand;

class AbstractChatCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        $cmd1 = new TestChatCommand('test', ['t'], true);
        $cmd2 = new TestChatCommand('test', ['t'], false);

        $this->assertEquals(['val1', 'val2'], $cmd1->parseParameters('val1 val2'));
        $this->assertEquals('val1 val2', $cmd2->parseParameters('val1 val2'));
        $this->assertEquals('test', $cmd2->getCommand());
        $this->assertEquals(['t'], $cmd2->getAliases());
        $this->assertTrue($cmd2->validate('test', ''));
    }
}
