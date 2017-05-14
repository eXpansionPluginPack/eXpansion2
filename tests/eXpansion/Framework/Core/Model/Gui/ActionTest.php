<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 13:11
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\Gui\Action;

class ActionTest extends \PHPUnit_Framework_TestCase
{

    protected $executed = false;

    public function testAction()
    {
        $action = new Action([$this, 'callbackTestAction'], ['toto']);

        $this->executed = false;
        $action->execute('yop', 'lop');
        $this->assertTrue($this->executed);
    }

    public function testUnicity()
    {
        $actionbyIds = array();

        for($i = 0; $i < 20; $i++) {
            $action = new Action([$this, 'callbackTestAction'], ['toto']);

            $this->assertArrayNotHasKey($action->getId(), $actionbyIds);
            $actionbyIds[$action->getId()] = $action;
        }
    }

    public function callbackTestAction($login, $answerValues, $arguments)
    {
        $this->assertEquals('yop', $login);
        $this->assertEquals('lop', $answerValues);
        $this->assertEquals(['toto'], $arguments);

        $this->executed = true;
    }


}
