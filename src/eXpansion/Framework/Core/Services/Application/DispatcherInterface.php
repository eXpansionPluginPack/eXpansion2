<?php


namespace eXpansion\Framework\Core\Services\Application;


use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

interface DispatcherInterface
{
    /**
     * Initialize the dispatcher and it's dependencies.
     *
     * @param $connection
     *
     * @return void
     */
    public function init(Connection $connection);


    /**
     * Reset the dispatcher elements when game mode changes.
     *
     * @param Map $map Current map.
     *
     * @return void
     */
    public function reset(Map $map);

    /**
     * Dispatch the event.
     *
     * @param $event
     * @param $params
     *
     * @return mixed
     */
    public function dispatch($event, $params);
}
