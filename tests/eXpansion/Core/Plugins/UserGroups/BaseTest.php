<?php

namespace Tests\eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\UserGroups\AllPlayers;
use eXpansion\Core\Plugins\UserGroups\Players;
use eXpansion\Core\Plugins\UserGroups\Spectators;
use eXpansion\Core\Storage\Data\Player;
use Tests\eXpansion\Core\TestCore;
use Tests\eXpansion\Core\TestHelpers\PlayerDataTrait;

class BaseTest extends TestCore
{
    use PlayerDataTrait;

    /** @var  AllPlayers */
    protected $pluginAllPlayers;
    /** @var  Spectators */
    protected $pluginSpectators;
    /** @var  Players */
    protected $pluginPlayers;

    /** @var  Group */
    protected $groupAllPlayers;
    /** @var  Group */
    protected $groupSpectators;
    /** @var  Group */
    protected $groupPlayers;


    protected function setUp()
    {
        parent::setUp();

        $this->pluginAllPlayers = $this->getAllPlayersGroupPlugin();
        $this->pluginSpectators = $this->getSpectatorsGroupPlugin();
        $this->pluginPlayers = $this->getPlayersGroupPlugin();

        $this->groupAllPlayers = $this->getAllPlayersGroup();
        $this->groupSpectators = $this->getSpectatorsGroup();
        $this->groupPlayers = $this->getPlayersGroup();
    }


    public function testOnPlayerConnect()
    {
        $p1 = $this->getPlayer('l1', false);
        $p2 = $this->getPlayer('l2', false);

        $this->playerConnect($p1);
        $this->playerConnect($p2);

        $this->assertEquals(['l1', 'l2'], $this->groupAllPlayers->getLogins());
        $this->assertEquals(['l1', 'l2'], $this->groupPlayers->getLogins());
        $this->assertEmpty($this->groupSpectators->getLogins());
    }

    public function testOnPlayerDisconnect()
    {
        $p1 = $this->getPlayer('l1', false);
        $p2 = $this->getPlayer('l2', false);

        $this->playerConnect($p1);
        $this->playerConnect($p2);
        $this->pluginAllPlayers->onPlayerDisconnect($p1, '');
        $this->pluginSpectators->onPlayerDisconnect($p1, '');
        $this->pluginPlayers->onPlayerDisconnect($p1, '');

        $this->assertEquals(['l2'], $this->groupAllPlayers->getLogins());
        $this->assertEquals(['l2'], $this->groupPlayers->getLogins());
        $this->assertEmpty($this->groupSpectators->getLogins());
    }

    public function testOnPlayerChange()
    {
        $p1 = $this->getPlayer('l1', false);
        $p2 = $this->getPlayer('l2', false);

        $this->playerConnect($p1);
        $this->playerConnect($p2);

        $p1New = $this->getPlayer('l1', true);
        $this->pluginAllPlayers->onPlayerInfoChanged($p1, $p1New);
        $this->pluginSpectators->onPlayerInfoChanged($p1, $p1New);
        $this->pluginPlayers->onPlayerInfoChanged($p1, $p1New);

        $this->assertEquals(['l1', 'l2'], $this->groupAllPlayers->getLogins());
        $this->assertEquals(['l2'], $this->groupPlayers->getLogins());
        $this->assertEquals(['l1'], $this->groupSpectators->getLogins());
    }

    public function testOnAlliesChange()
    {
        $p1 = $this->getPlayer('l1', false);
        $p2 = $this->getPlayer('l2', false);

        $this->playerConnect($p1);
        $this->playerConnect($p2);

        $p1New = $this->getPlayer('l1', true);
        $this->pluginAllPlayers->onPlayerAlliesChanged($p1, $p1New);
        $this->pluginSpectators->onPlayerAlliesChanged($p1, $p1New);
        $this->pluginPlayers->onPlayerAlliesChanged($p1, $p1New);

        $this->assertEquals(['l1', 'l2'], $this->groupAllPlayers->getLogins());
        $this->assertEquals(['l1', 'l2'], $this->groupPlayers->getLogins());
        $this->assertEmpty($this->groupSpectators->getLogins());
    }

    protected function playerConnect(Player $player)
    {
        $this->pluginAllPlayers->onPlayerConnect($player);
        $this->pluginSpectators->onPlayerConnect($player);
        $this->pluginPlayers->onPlayerConnect($player);
    }


    /**
     * @return AllPlayers
     */
    protected function getAllPlayersGroupPlugin()
    {
        return $this->container->get('expansion.core.plugins.user_group.all_players');
    }

    /**
     * @return Group
     */
    protected function getAllPlayersGroup()
    {
        return $this->container->get('expansion.core.user_groups.all_players');
    }

    /**
     * @return Spectators
     */
    protected function getSpectatorsGroupPlugin()
    {
        return $this->container->get('expansion.core.plugins.user_group.spectators');
    }

    /**
     * @return Group
     */
    protected function getSpectatorsGroup()
    {
        return $this->container->get('expansion.core.user_groups.spectators');
    }

    /**
     * @return Spectators
     */
    protected function getPlayersGroupPlugin()
    {
        return $this->container->get('expansion.core.plugins.user_group.players');
    }

    /**
     * @return Group
     */
    protected function getPlayersGroup()
    {
        return $this->container->get('expansion.core.user_groups.players');
    }


}