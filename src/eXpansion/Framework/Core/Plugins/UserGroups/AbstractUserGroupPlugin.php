<?php


namespace eXpansion\Framework\Core\Plugins\UserGroups;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\Data\Player;

abstract class AbstractUserGroupPlugin implements ListenerInterfaceMpLegacyPlayer
{
    protected $userGroup;

    /**
     * AbstractUserGroupPlugin constructor.
     *
     * @param Group $userGroup
     */
    public function __construct(Group $userGroup)
    {
        $this->userGroup = $userGroup;

    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $this->userGroup->removeLogin($player->getLogin());
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // By default nothing.
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // By default nothing.
    }
}
