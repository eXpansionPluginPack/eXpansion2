<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 18:44
 */

namespace Tests\eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpUserGroup;
use eXpansion\Framework\Core\DataProviders\UserGroupDataProvider;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use Tests\eXpansion\Framework\Core\TestCore;

class UserGroupDataProviderTest extends TestCore
{
    public function testPlayerAdded()
    {
        /** @var Group $group */
        $group = $this->container->get('expansion.framework.core.user_groups.factory')->createForPlayers(['test']);

        $mockPlugin = $this->createMock(ListenerInterfaceExpUserGroup::class);
        $mockPlugin->expects($this->once())
            ->method('onExpansionGroupAddUser')
            ->with($group, 'test');

        $this->getUserGroupDataProvider()->registerPlugin('test', $mockPlugin);
        $this->getUserGroupDataProvider()->onExpansionGroupAddUser($group, 'test');
    }

    public function testPlayerRemove()
    {
        /** @var Group $group */
        $group = $this->container->get('expansion.framework.core.user_groups.factory')->createForPlayers(['test']);

        $mockPlugin = $this->createMock(ListenerInterfaceExpUserGroup::class);
        $mockPlugin->expects($this->once())
            ->method('onExpansionGroupRemoveUser')
            ->with($group, 'test');

        $this->getUserGroupDataProvider()->registerPlugin('test', $mockPlugin);
        $this->getUserGroupDataProvider()->onExpansionGroupRemoveUser($group, 'test');
    }

    public function testDestroy()
    {
        /** @var Group $group */
        $group = $this->container->get('expansion.framework.core.user_groups.factory')->createForPlayers(['test']);

        $mockPlugin = $this->createMock(ListenerInterfaceExpUserGroup::class);
        $mockPlugin->expects($this->once())
            ->method('onExpansionGroupDestroy')
            ->with($group, 'test');

        $this->getUserGroupDataProvider()->registerPlugin('test', $mockPlugin);
        $this->getUserGroupDataProvider()->onExpansionGroupDestroy($group, 'test');
    }

    /**
     * @return UserGroupDataProvider|object
     */
    public function getUserGroupDataProvider()
    {
        return $this->container->get('expansion.framework.core.data_providers.user_group_provider');
    }
}
