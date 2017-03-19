<?php

namespace eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\Storage\Data\Player;

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