<?php

namespace Tests\eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\Plugins\UserGroups\Factory;
use Tests\eXpansion\Core\TestCore;


class FactoryTest extends TestCore
{
    public function testCreatePlayerGroup()
    {
        $factory = $this->getGroupFactory();

        $group = $factory->createForPlayer('test');
        $groupNew = $factory->createForPlayer('test');
        $this->assertSame($group, $groupNew);

        $group->removeLogin('test');
        $factory->onExpansionGroupRemoveUser($group, 'test');
        $groupNew = $factory->createForPlayer('test');
        $this->assertNotSame($group, $groupNew);
    }

    public function testCreatePlayersGroup()
    {
        $factory = $this->getGroupFactory();

        $group = $factory->createForPlayers(['test', 'test1']);
        $this->assertSame($group, $factory->getGroup($group->getName()));

        $group->removeLogin('test');
        $factory->onExpansionGroupRemoveUser($group, 'test');
        $group->removeLogin('test1');
        $factory->onExpansionGroupRemoveUser($group, 'test1');
        $factory->onExpansionGroupDestroy($group, 'test1');

        $this->assertNull($factory->getGroup($group->getName()));
    }

    public function testEmptyFunction()
    {
        $factory = $this->getGroupFactory();
        $group = $factory->createForPlayer('test');

        $factory->onExpansionGroupAddUser($group, 'test');
    }

    /**
     *
     * @return Factory
     */
    protected function getGroupFactory()
    {
        return $this->container->get('expansion.core.user_groups.factory');
    }
}
