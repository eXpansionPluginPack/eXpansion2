<?php

namespace eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\Storage\Data\Player;

class Spectators extends AbstractUserGroupPlugin
{
    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        if ($player->isSpectator()) {
            $this->userGroup->addLogin($player->getLogin());
        }
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        if ($player->getSpectatorStatus() != 0) {
            $this->userGroup->addLogin($player->getLogin());
        } else {
            $this->userGroup->removeLogin($player->getLogin());
        }
    }
}