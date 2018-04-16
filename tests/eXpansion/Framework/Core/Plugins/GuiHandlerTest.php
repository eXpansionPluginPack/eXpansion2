<?php
/**
 * File GuiHandlerTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Psr\Log\LoggerInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\ManialinkDataTrait;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class GuiHandlerTest extends TestCore
{
    use ManialinkDataTrait;
    use PlayerDataTrait;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockLogger;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockActionFactory;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockMlFactory;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConsoleHelper;

    /** @var  GuiHandler */
    protected $guiHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->mockLogger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $this->mockActionFactory = $this->getMockBuilder(ActionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockConsoleHelper = $this->getMockBuilder(Console::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockMlFactory = $this->getMockBuilder(ManialinkFactoryInterface::class)
            ->getMock();
        $this->mockMlFactory->method("getId")->willReturn("abc");

        $this->guiHandler = new GuiHandler(
            $this->mockConnectionFactory,
            $this->mockLogger,
            $this->mockConsoleHelper,
            $this->mockActionFactory
        );
    }

    public function testSendManialink()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, $manialink->getXml());

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
    }

    public function testHideManialink()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, '<manialink id="' . $manialink->getId() . '" />');

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToHide($manialink);

        $guiHanlder->onPostLoop();
    }

    public function testShowHideShow()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, $manialink->getXml());

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);
        $guiHanlder->addToHide($manialink);
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
    }

    public function testShowPostHide()
    {

        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], [$logins, '<manialink id="' . $manialink->getId() . '" />']);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);
        $guiHanlder->onPostLoop();

        $guiHanlder->addToHide($manialink);
        $guiHanlder->onPostLoop();
    }

    public function testConnect()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);
        
        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], [['test3'], $manialink->getXml()]);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test3');
        $guiHanlder->onPostLoop();
    }

    public function testMultipleConnect()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], [['test3', 'test4', 'test5'], $manialink->getXml()]);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test3');
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test4');
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test5');
        $guiHanlder->onPostLoop();
    }

    public function testGroupRemoveUser()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()], [['test1'], '<manialink id="' . $manialink->getId() . '" />']);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onPostLoop();
    }

    public function testMultipleGroupRemoveUser()
    {
        $logins = ['test1', 'test2', 'test3', 'test4'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive(
                [$logins, $manialink->getXml()],
                [['test1', 'test2'], '<manialink id="' . $manialink->getId() . '" />']
            );

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test2');
        $guiHanlder->onPostLoop();
    }

    public function testDestroy()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->with($logins, $manialink->getXml());

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupDestroy($manialink->getUserGroup(), 'test1');

        $this->assertEmpty($guiHanlder->getDisplayeds());
    }

    public function testDisconnect()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->once())
            ->method('sendDisplayManialinkPage')
            ->withConsecutive([$logins, $manialink->getXml()]);

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);

        $guiHanlder->onPostLoop();
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onPlayerDisconnect($this->getPlayer('test1', false), '');
        $guiHanlder->onPostLoop();
    }

    public function testExtreme()
    {
        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withAnyParameters();
        $this->mockConnection->expects($this->exactly(2))
            ->method('executeMulticall')
            ->withAnyParameters();
        $logins = ['test1', 'test2'];

        $guiHanlder = $this->guiHandler;
        $guiHanlder->setCharLimit(160);

        for ($i = 0; $i < 2; $i++) {
            $mockMlFactory = $this->getMockBuilder(ManialinkFactoryInterface::class)
                ->getMock();
            $mockMlFactory->method('getId')->willReturn('abc-' . $i);

            $manialink = $this->getManialink($logins, $mockMlFactory);
            $guiHanlder->addToDisplay($manialink);
        }

        $guiHanlder->onPostLoop();
    }

    public function testError()
    {
        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withAnyParameters();
        $this->mockConnection->method('executeMulticall')
            ->will($this->throwException(new \Exception));
        $logins = ['test1', 'test2'];

        $this->mockConsoleHelper->expects($this->exactly(2))->method('writeln');

        $guiHanlder = $this->guiHandler;
        $guiHanlder->setCharLimit(160);

        for ($i = 0; $i < 2; $i++) {
            $mockMlFactory = $this->getMockBuilder(ManialinkFactoryInterface::class)
                ->getMock();
            $mockMlFactory->method('getId')->willReturn('abc-' . $i);

            $manialink = $this->getManialink($logins, $mockMlFactory);
            $guiHanlder->addToDisplay($manialink);
        }

        $guiHanlder->onPostLoop();
    }

    /**
     * When Gui handler received show then hide order it needs to hide the ml.
     */
    public function testShowHide()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->exactly(3))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive(
                [$logins, $manialink->getXml()],
                [['test1'], '<manialink id="' . $manialink->getId() . '" />'],
                [['test1'], '<manialink id="' . $manialink->getId() . '" />']
            );

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);
        $guiHanlder->onPostLoop();

        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onPostLoop();

        // Execute test scenario
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onPostLoop();
    }

    /**
     * When Gui handler received hide then show order it needs to show the ml.
     */
    public function testHideShow()
    {
        $logins = ['test1', 'test2'];
        $manialink = $this->getManialink($logins, $this->mockMlFactory);

        $this->mockConnection->expects($this->exactly(2))
            ->method('sendDisplayManialinkPage')
            ->withConsecutive(
                [$logins, $manialink->getXml()],
                [['test1'], $manialink->getXml()]
            );

        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;
        $guiHanlder->addToDisplay($manialink);
        $guiHanlder->onPostLoop();

        // Execute test scenario
        $guiHanlder->onExpansionGroupRemoveUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onExpansionGroupAddUser($manialink->getUserGroup(), 'test1');
        $guiHanlder->onPostLoop();
    }

    public function testEmptyMethods()
    {
        /** @var GuiHandler $guiHanlder */
        $guiHanlder = $this->guiHandler;

        $guiHanlder->onPreLoop();
        $guiHanlder->onEverySecond();
        $guiHanlder->onPlayerConnect($this->getPlayer('test', false));
        $guiHanlder->onPlayerInfoChanged(
            $this->getPlayer('test', false),
            $this->getPlayer('test', false)
        );
        $guiHanlder->onPlayerAlliesChanged(
            $this->getPlayer('test', false),
            $this->getPlayer('test', false)
        );
    }
}
