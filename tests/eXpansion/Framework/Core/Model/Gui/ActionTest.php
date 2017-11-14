<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 13:11
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\Gui\Action;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\ManialinkDataTrait;

class ActionTest extends TestCore
{
    use ManialinkDataTrait;

    protected $executed = false;

    protected $manialink;


    public function testAction()
    {
        $this->manialink = $this->getManialink(['yop']);

        $action = new Action([$this, 'callbackTestAction'], ['toto']);

        $this->executed = false;
        $action->execute($this->manialink, 'yop', 'lop');
        $this->assertTrue($this->executed);
    }

    public function testUnicity()
    {
        $actionbyIds = array();

        for ($i = 0; $i < 20; $i++) {
            $action = new Action([$this, 'callbackTestAction'], ['toto']);

            $this->assertArrayNotHasKey($action->getId(), $actionbyIds);
            $actionbyIds[$action->getId()] = $action;
        }
    }

    public function callbackTestAction(ManialinkInterface $manialink, $login, $answerValues, $arguments)
    {
        $this->assertEquals($this->manialink, $manialink);
        $this->assertEquals('yop', $login);
        $this->assertEquals('lop', $answerValues);
        $this->assertEquals(['toto'], $arguments);

        $this->executed = true;
    }


}
