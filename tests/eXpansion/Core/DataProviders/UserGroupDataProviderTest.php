<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 18:44
 */

namespace Tests\eXpansion\Core\DataProviders;

use eXpansion\Core\DataProviders\Listener\UserGroupDataListenerInterface;
use eXpansion\Core\DataProviders\UserGroupDataProvider;
use eXpansion\Core\Model\UserGroups\Group;
use Tests\eXpansion\Core\TestCore;

class UserGroupDataProviderTest extends TestCore
{
    public function testPlayerAdded()
    {
        /** @var Group $group */
        $group = $this->container->get('expansion.core.user_groups.factory')->createForPlayers(['test']);

        $mockPlugin = $this->createMock(UserGroupDataListenerInterface::class);
        $mockPlugin->expects($this->once())
            ->method('onExpansionGroupAddUser')
            ->with($group, 'test');

        $this->getUserGroupDataProvider()->registerPlugin('test', $mockPlugin);
        $this->getUserGroupDataProvider()->onExpansionGroupAddUser($group, 'test');
    }

    public function testPlayerRemove()
    {
        /** @var Group $group */
        $group = $this->container->get('expansion.core.user_groups.factory')->createForPlayers(['test']);

        $mockPlugin = $this->createMock(UserGroupDataListenerInterface::class);
        $mockPlugin->expects($this->once())
            ->method('onExpansionGroupRemoveUser')
            ->with($group, 'test');

        $this->getUserGroupDataProvider()->registerPlugin('test', $mockPlugin);
        $this->getUserGroupDataProvider()->onExpansionGroupRemoveUser($group, 'test');
    }

    public function testDestroy()
    {
        /** @var Group $group */
        $group = $this->container->get('expansion.core.user_groups.factory')->createForPlayers(['test']);

        $mockPlugin = $this->createMock(UserGroupDataListenerInterface::class);
        $mockPlugin->expects($this->once())
            ->method('onExpansionGroupDestroy')
            ->with($group, 'test');

        $this->getUserGroupDataProvider()->registerPlugin('test', $mockPlugin);
        $this->getUserGroupDataProvider()->onExpansionGroupDestroy($group, 'test');
    }

    /**
     * @return UserGroupDataProvider
     */
    public function getUserGroupDataProvider()
    {
        return $this->container->get('expansion.core.data_providers.user_group_provider');
    }
}
