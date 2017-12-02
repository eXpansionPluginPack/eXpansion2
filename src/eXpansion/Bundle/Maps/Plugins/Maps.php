<?php

namespace eXpansion\Bundle\Maps\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMaplist;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;


class Maps implements ListenerInterfaceMpLegacyMap, ListenerInterfaceMpLegacyMaplist, StatusAwarePluginInterface
{
    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var AdminGroups */
    protected $adminGroups;

    /** @var bool */
    protected $enabled = true;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var ChatNotification */
    protected $chatNotification;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * Maps constructor.
     * @param Connection $connection
     * @param Console $console
     * @param AdminGroups $adminGroups
     * @param MapStorage $mapStorage
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     */
    function __construct(
        Connection $connection,
        Console $console,
        AdminGroups $adminGroups,
        MapStorage $mapStorage,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->adminGroups = $adminGroups;
        $this->mapStorage = $mapStorage;
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     */
    public function setStatus($status)
    {

    }

    /**
     * @return Map[]
     */
    public function getMaps()
    {
        return $this->mapStorage->getMaps();
    }


    public function removeMap($login, $uid)
    {
        if (!$this->adminGroups->hasPermission($login, 'maps.remove')) {
            $this->chatNotification->sendMessage('expansion_maps.chat.nopermission', $login);
        }

        if ($uid == "this") {
            $map = $this->mapStorage->getCurrentMap();
        } else {
            if (is_numeric($uid)) {
                $map = $this->mapStorage->getMapByIndex($uid);
            } else {
                $map = $this->mapStorage->getMap($uid);
            }
        }

        $group = $this->adminGroups->getLoginUserGroups($login);
        $level = $this->adminGroups->getGroupLabel($group->getName());
        $nickname = $this->playerStorage->getPlayerInfo($login)->getNickName();
        try {
            $this->connection->removeMap($map->fileName);
            $this->chatNotification->sendMessage('expansion_maps.chat.removemap', null,
                ["%level%" => $level, "%admin%" => $nickname, "%map%" => TMString::trimControls($map->name)]);
        } catch (\Exception $e) {
            $this->chatNotification->sendMessage('expansion_maps.chat.dedicatedexception', $group,
                ["%message%" => $e->getMessage()]);
        }
    }

    /**
     * @param \Maniaplanet\DedicatedServer\Structures\Map[] $oldMaps
     * @param string $currentMapUid
     * @param string $nextMapUid
     * @param bool $isListModified
     * @return mixed|void
     */
    public function onMapListModified($oldMaps, $currentMapUid, $nextMapUid, $isListModified)
    {

    }

    /**
     * @param $currentMap
     * @param $previousMap
     */
    public function onExpansionMapChange($currentMap, $previousMap)
    {
        // TODO: Implement onExpansionMapChange() method.
    }

    /**
     * @param $nextMap
     * @param $previousNextMap
     */
    public function onExpansionNextMapChange($nextMap, $previousNextMap)
    {
        // TODO: Implement onExpansionNextMapChange() method.
    }

    /**
     * @param Map $map
     *
     * @return mixed
     */
    public function onBeginMap(Map $map)
    {

    }

    /**
     * @param Map $map
     *
     * @return mixed
     */
    public function onEndMap(Map $map)
    {
        // TODO: Implement onEndMap() method.
    }

}
