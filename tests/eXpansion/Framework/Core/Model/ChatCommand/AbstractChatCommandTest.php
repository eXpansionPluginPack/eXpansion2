<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 17:41
 */

namespace Tests\eXpansion\Framework\Core\Model\ChatCommand;

use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestChatCommand;

class AbstractChatCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->assertEquals('test', $cmd2->getCommand());
        $this->assertEquals(['t'], $cmd2->getAliases());
    }
}
