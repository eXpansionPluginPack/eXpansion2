<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 13:22
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Framework\Core\Model\Gui\Action;
use eXpansion\Framework\Core\Model\Gui\ManiaScript;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class WindowTest extends TestCore
{

    public function testWindow()
    {
        /** @var ManiaScriptFactory $factory */
        $factory = $this->container->get('expansion.framework.core.mania_script.window_factory');
        $action = new Action([$this, 'totoCallback'], []);

        $window = new Window($this->getSpectatorsGroup(), $factory, 'test', 10, 20);
        $window->setCloseAction($action->getId());

        $this->assertInstanceOf(\SimpleXMLElement::class, simplexml_load_string($window->getXml()));
    }

    public function testWindowWithoutAction()
    {
        /** @var ManiaScriptFactory $factory */
        $factory = $this->container->get('expansion.framework.core.mania_script.window_factory');
        $window = new Window($this->getSpectatorsGroup(), $factory, 'test', 10, 20);

        $this->expectException(MissingCloseActionException::class);

        $window->getXml();
    }

    public function testMethods()
    {
        /** @var ManiaScriptFactory $factory */
        $factory = $this->container->get('expansion.framework.core.mania_script.window_factory');
        $window = new Window($this->getSpectatorsGroup(), $factory, 'test', 10, 20);

        $mockRenderable = $this->createMock(Renderable::class);

        $window->addChild($mockRenderable);
        $this->assertEquals([$mockRenderable], $window->getChildren());
        $window->add($mockRenderable);
        $this->assertEquals([$mockRenderable], $window->getChildren());

        $window->removeAllChildren();
        $this->assertEmpty($window->getChildren());

        $window->addChildren([$mockRenderable]);
        $this->assertEquals([$mockRenderable], $window->getChildren());

        $window->removeChildren();
        $this->assertEmpty($window->getChildren());

        $format = $window->getFormat();
        $window->setFormat($format);
    }

    /**
     * @return Group
     */
    protected function getSpectatorsGroup()
    {
        return $this->container->get('expansion.framework.core.user_groups.spectators');
    }
}
