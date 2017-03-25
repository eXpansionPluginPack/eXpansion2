<?php

namespace Tests\eXpansion\Core\Plugins\Gui;

use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Core\Plugins\GuiHandler;
use eXpansion\Core\Plugins\UserGroups\Factory;
use eXpansion\Core\Services\Application\DispatcherInterface;
use Tests\eXpansion\Core\TestCore;

class ManialinkFactoryTest extends TestCore
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $guiHandlerMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $userGroupFactoryMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $dispatcherMock;

    protected function setUp()
    {
        parent::setUp();

        $this->guiHandlerMock = $this->createMock(GuiHandler::class);
        $this->userGroupFactoryMock = $this->createMock(Factory::class);
        $this->dispatcherMock = $this->createMock(DispatcherInterface::class);
    }

    public function testCreateForGroup()
    {
        $group = new Group($this->dispatcherMock, 'test');
        $group2 = new Group($this->dispatcherMock, 'test2');

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(2))->method('addToDisplay');

        $mlFactory->create($group);
        $mlFactory->create($group2);
    }

    public function testCreateForLogin()
    {
        $group = new Group($this->dispatcherMock, 'test1');
        $group2 = new Group($this->dispatcherMock, 'test2');

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
        $group = new Group($this->dispatcherMock, 'test1');

        $this->userGroupFactoryMock->method('createForPlayers')
            ->with(['test1', 'test2'])
            ->willReturn($group);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(1))->method('addToDisplay');

        $mlFactory->create(['test1', 'test2']);
    }

    public function testDestory()
    {
        $group = new Group($this->dispatcherMock, 'test1');

        $this->userGroupFactoryMock->method('createForPlayers')
            ->with(['test1', 'test2'])
            ->willReturn($group);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(1))->method('addToDisplay');
        $this->guiHandlerMock->expects($this->exactly(1))->method('addToHide');

        $mlFactory->create(['test1', 'test2']);
        $mlFactory->destroy($group);
    }

    public function testGroupDestory()
    {
        $group = new Group($this->dispatcherMock, 'test1');

        $this->userGroupFactoryMock->method('createForPlayers')
            ->with(['test1', 'test2'])
            ->willReturn($group);

        $mlFactory = $this->getManialinkFactory();

        $this->guiHandlerMock->expects($this->exactly(1))->method('addToDisplay');
        $this->guiHandlerMock->expects($this->exactly(0))->method('addToHide');

        $mlFactory->create(['test1', 'test2']);
        $mlFactory->onExpansionGroupDestroy($group, 'test1');
    }

    public function testEmptyMethods()
    {
        $group = new Group($this->dispatcherMock, 'test1');
        $mlFactory = $this->getManialinkFactory();

        $mlFactory->onExpansionGroupAddUser($group, 'test1');
        $mlFactory->onExpansionGroupRemoveUser($group, 'test1');
    }

    protected function getManialinkFactory()
    {
        return new ManialinkFactory(
            $this->guiHandlerMock,
            $this->userGroupFactoryMock,
            'test',
            2,
            2
        );
    }
}
