<?php

namespace eXpansion\Framework\Core\Plugins\UserGroups;

use eXpansion\Framework\Core\Storage\Data\Player;

class AllPlayers extends AbstractUserGroupPlugin
{
    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $this->userGroup->addLogin($player->getLogin());
    }
}