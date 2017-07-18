<?php

namespace eXpansion\Framework\AdminGroups\Plugins;

use eXpansion\Framework\AdminGroups\Services\AdminGroupConfiguration;
use eXpansion\Framework\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use eXpansion\Framework\Core\Storage\Data\Player;

/**
 * Class GroupsPlugin will keep the admin groups uptodate with the proper player information.
 *
 * @package eXpansion\Bundle\AdminGroupConfiguration\Plugins;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class GroupsPlugin implements PlayerDataListenerInterface
{
    /** @var  AdminGroupConfiguration */
    protected $adminGroupConfiguration;

    /** @var  Factory */
    protected $userGroupFactory;

    /**
     * GroupsPlugin constructor.
     *
     * @param AdminGroupConfiguration $adminGroupConfiguration
     * @param Factory $userGroupFactory
     */
    public function __construct(
        AdminGroupConfiguration $adminGroupConfiguration,
        Factory $userGroupFactory
    ) {
        $this->adminGroupConfiguration = $adminGroupConfiguration;
        $this->userGroupFactory = $userGroupFactory;

        foreach ($this->adminGroupConfiguration->getGroups() as $groupName) {
            $this->userGroupFactory->create("admin:$groupName");
        }

        $this->userGroupFactory->create('admin:guest');
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $groupName = $this->adminGroupConfiguration->getLoginGroupName($player->getLogin());

        if ($groupName === null) {
            $this->userGroupFactory->getGroup('admin:guest')->addLogin($player->getLogin());
        } else {
            $this->userGroupFactory->getGroup("admin:$groupName")->addLogin($player->getLogin());
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // Nothing to do here, user group factory will handle it.
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // nothing to do here, info changed don't affect admin status of a player.
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // nothing to do here, info changed don't affect admin status of a player.
    }
}
