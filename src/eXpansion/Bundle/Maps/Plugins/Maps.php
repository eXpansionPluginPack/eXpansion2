<?php

namespace eXpansion\Bundle\Maps\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMaplist;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\MapStorage;
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

    function __construct(
        Connection $connection,
        Console $console,
        AdminGroups $adminGroups,
        MapStorage $mapStorage,
        ChatNotification $chatNotification
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->adminGroups = $adminGroups;
        $this->mapStorage = $mapStorage;
        $this->chatNotification = $chatNotification;
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
        if (!$status) {

        }
    }

    /**
     * @return Map[]
     */
    public function getMaps()
    {
        return $this->mapStorage->getMaps();
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

    public function onExpansionMapChange($currentMap, $previousMap)
    {
        // TODO: Implement onExpansionMapChange() method.
    }

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
        $this->chatNotification->sendMessage('expansion_maps.chat.onbeginmap', null, ['%name%' => $map->name, '%author%' => $map->author]);
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
