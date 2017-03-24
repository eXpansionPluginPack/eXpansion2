<?php
/**
 * File GuiHandlerTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Core\Plugins;

use eXpansion\Core\Model\Gui\Manialink;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\GuiHandler;
use Tests\eXpansion\Core\TestCore;

class GuiHandlerTest extends TestCore
{

    public function testSendManialink()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, $manialink->getXml());

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
    }

    public function testHideManialink()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, '<manialink id="' . $manialink->getId() . '" />');

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToHide($manialink);

        $guiHanlder->onPostLoop();
    }

    public function testShowHideShow()
    {

        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, $manialink->getXml());

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToDisplay($manialink);
        $guiHanlder->addToHide($manialink);
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
    }

    public function testShowPostHide()
    {

        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], [$logins, '<manialink id="' . $manialink->getId() . '" />']);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToDisplay($manialink);
        $guiHanlder->onPostLoop();

        $guiHanlder->addToHide($manialink);
        $guiHanlder->onPostLoop();
    }

    public function testConnect()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], ['test3', $manialink->getXml()]);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test3');
        $guiHanlder->onPostLoop();
    }

    public function testDisconnectConnect()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], ['test1', '<manialink id="' . $manialink->getId() . '" />']);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onPostLoop();
    }

    public function testDestroy()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins);


        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->container->get('expansion.core.services.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, $manialink->getXml());

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupDestroy($manialink->getUserGroup(), 'test1');

        $this->assertEmpty($guiHanlder->getDisplayeds());
    }

    public function testEmptyMethods()
    {
        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->container->get('expansion.core.plugins.gui_handler');

        $guiHanlder->onPreLoop();
        $guiHanlder->onEverySecond();
    }

    protected function getManialink($logins)
    {
        $group = new Group($this->container->get('expansion.core.services.application.dispatcher'), "test");
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        $manialink = new Manialink($group, 'test', 1, 1,1,1);

        return $manialink;
    }
}
