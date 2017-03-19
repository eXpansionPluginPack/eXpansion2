<?php


namespace eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Storage\Data\Player;

abstract class AbstractUserGroupPlugin  implements PlayerDataListenerInterface
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