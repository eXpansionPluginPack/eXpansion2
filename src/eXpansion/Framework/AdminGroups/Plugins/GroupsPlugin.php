<?php

namespace eXpansion\Framework\AdminGroups\Plugins;

use eXpansion\Framework\AdminGroups\Services\AdminGroupConfiguration;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;

/**
 * Class GroupsPlugin will keep the admin groups uptodate with the proper player information.
 *
 * @package eXpansion\Bundle\AdminGroupConfiguration\Plugins;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class GroupsPlugin implements ListenerInterfaceMpLegacyPlayer, StatusAwarePluginInterface
{
    /** @var  AdminGroupConfiguration */
    protected $adminGroupConfiguration;

    /** @var  Factory */
    protected $userGroupFactory;

    /** @var  PlayerStorage */
    protected $playerStorage;

    /**
     * GroupsPlugin constructor.
     *
     * @param AdminGroupConfiguration $adminGroupConfiguration
     * @param Factory $userGroupFactory
     */
    public function __construct(
        AdminGroupConfiguration $adminGroupConfiguration,
        Factory $userGroupFactory,
        PlayerStorage $playerStorage
    ) {
        $this->adminGroupConfiguration = $adminGroupConfiguration;
        $this->userGroupFactory = $userGroupFactory;
        $this->playerStorage = $playerStorage;

        // Create a user group for each admin group & for guest players.
        foreach ($this->adminGroupConfiguration->getGroups() as $groupName) {
            $this->userGroupFactory->create("admin:$groupName");
        }
        $this->userGroupFactory->create('admin:guest');
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        if ($status && !empty($this->playerStorage->getOnline())) {
            foreach ($this->playerStorage->getOnline() as $player) {
                $this->onPlayerConnect($player);
            }
        }
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
