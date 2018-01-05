<?php

namespace Tests\eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\ManialinkDataTrait;


class ActionFactoryTest extends TestCore
{
    use ManialinkDataTrait;

    protected $called = false;

    protected $manialink;

    protected function setUp()
    {
        parent::setUp(); 
        $this->manialink = $this->getManialink(['test1']);
    }

    public function testAction()
    {
        /** @var ActionFactory $actionFactory */
        $actionFactory = $this->container->get(ActionFactory::class);
        $actionId = $actionFactory->createManialinkAction($this->manialink, array($this, 'actionCall'), ['testParam']);
        $actionFactory->onPlayerManialinkPageAnswer('test1', $actionId, ['entry' => 'value1']);

        $this->assertTrue($this->called);
    }

    public function testDestroy()
    {
        $this->manialink = $this->getManialink(['test1']);

        /** @var ActionFactory $actionFactory */
        $actionFactory = $this->container->get(ActionFactory::class);
        $actionId = $actionFactory->createManialinkAction($this->manialink, array($this, 'actionCall'), ['testParam']);
        $actionFactory->destroyManialinkActions($this->manialink);
        $actionFactory->onPlayerManialinkPageAnswer('test1', $actionId, ['entry' => 'value1']);

        $this->assertFalse($this->called);
    }

    public function actionCall(ManialinkInterface $manialink, $login, $answerValues, $arguments)
    {
        $this->called = true;
        $this->assertEquals($this->manialink, $manialink);
        $this->assertEquals('test1', $login);
        $this->assertEquals(['entry' => 'value1'], $answerValues);
        $this->assertEquals(['testParam'], $arguments);
    }

}
