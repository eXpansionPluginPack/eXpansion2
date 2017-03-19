<?php

namespace eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\Storage\Data\Player;

class Players extends Spectators
{
    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        if (!$player->isSpectator()) {
            $this->userGroup->addLogin($player->getLogin());
        }
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        $this->userGroup->removeLogin($player->getLogin());
        $this->onPlayerConnect($player);
    }
}