<?php


namespace Tests\eXpansion\Core\TestHelpers;


use eXpansion\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;

trait PlayerDataTrait
{
    protected function getPlayer($login, $spectator)
    {
        $playerI = new PlayerInfo();
        $playerI->isServer = false;
        $playerI->spectator = $spectator;
        $playerD = new PlayerDetailedInfo();
        $playerD->login = $login;
        $playerD->clientVersion = 'client-test';
        $playerD->nickName = '$fff' . $login;

        $player = new Player();
        $player->merge($playerI);
        $player->merge($playerD);

        return $player;
    }
}