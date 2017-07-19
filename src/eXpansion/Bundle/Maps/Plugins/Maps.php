<?php

namespace eXpansion\Bundle\Maps\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\MapDataListenerInterface;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\MapStorage;
use Maniaplanet\DedicatedServer\Connection;


class Maps implements MapDataListenerInterface, StatusAwarePluginInterface
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

    function __construct(Connection $connection, Console $console, AdminGroups $adminGroups, MapStorage $mapStorage)
    {
        $this->connection = $connection;
        $this->console = $console;
        $this->adminGroups = $adminGroups;
        $this->mapStorage = $mapStorage;
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
     * @return array
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
}
