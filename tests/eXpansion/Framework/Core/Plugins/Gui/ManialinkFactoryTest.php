<?php

namespace Tests\eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use PHPUnit\Framework\TestCase;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\ManialinkDataTrait;

class ManialinkFactoryTest extends TestCase
{
    use ManialinkDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $guiHandlerMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $userGroupFactoryMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $dispatcherMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $actionFactoryMock;

    protected function setUp()
    {
        parent::setUp();

        $this->guiHandlerMock = $this->createMock(GuiHandler::class);
        $this->userGroupFactoryMock = $this->createMock(Factory::class);
        $this->dispatcherMock = $this->createMock(DispatcherInterface::class);
        $this->actionFactoryMock = $this->createMock(ActionFactory::class);
    }

    public function testCreateForGroup()
    {
        $group = new Group('test', $this->dispatcherMock);
        $group2 = new Group('test2', $this->dispatcherMock);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(2))->method('addToDisplay');

        $mlFactory->create($group);
        $mlFactory->create($group2);
    }

    public function testCreateForLogin()
    {
        $group = new Group('test1', $this->dispatcherMock);
        $group2 = new Group('test2', $this->dispatcherMock);

        $this->userGroupFactoryMock->method('createForPlayer')
            ->withConsecutive(['test1'], ['test2'])
            ->willReturn($group, $group2);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(2))->method('addToDisplay');

        $mlFactory->create('test1');
        $mlFactory->create('test2');
    }

    public function testCreateForLogins()
    {
        $group = new Group('test1', $this->dispatcherMock);

        $this->userGroupFactoryMock->method('createForPlayers')
            ->with(['test1', 'test2'])
            ->willReturn($group);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(1))->method('addToDisplay');

        $mlFactory->create(['test1', 'test2']);
    }

    public function testDestory()
    {
        $group = new Group('test1', $this->dispatcherMock);

        $this->userGroupFactoryMock->method('createForPlayers')
            ->with(['test1', 'test2'])
            ->willReturn($group);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->method('getManialink')->willReturn($this->getManialink([]));
        $this->guiHandlerMock->expects($this->exactly(1))->method('addToDisplay');
        $this->guiHandlerMock->expects($this->exactly(1))->method('addToHide');

        $mlFactory->create(['test1', 'test2']);
        $mlFactory->destroy($group);
    }


    protected function getManialinkFactory()
    {
        $context = new ManialinkFactoryContext(
            'eXpansion\Framework\Core\Model\Gui\Manialink',
            $this->guiHandlerMock,
            $this->userGroupFactoryMock,
            $this->actionFactoryMock
        );

        return new ManialinkFactory(
            'test',
            2,
            2,
            null,
            null,
            $context
        );
    }
}
