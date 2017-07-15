<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\MapStorage;

/**
 * Class MapDataProvider
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class MapDataProvider extends AbstractDataProvider
{
    /** @var  MapStorage */
    protected $mapStorage;

    /**
     * BaseDataProvider constructor.
     *
     * @param MapStorage $mapStorage
     */
    public function __construct(MapStorage $mapStorage)
    {
        $this->mapStorage = $mapStorage;
    }

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param array $params
     */
    public function onStartMapStart($params)
    {
        $this->dispatchMapEvent('onStartMapStart', $params);
    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param array $params
     */
    public function onStartMapEnd($params)
    {
        $this->dispatchMapEvent('onStartMapEnd', $params);
    }


    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param array $params
     */
    public function onEndMapStart($params)
    {
        $this->dispatchMapEvent('onEndMapStart', $params);
    }

    /**
     * Callback sent when the "EndMap" section ends.
     *
     * @param array $params
     */
    public function onEndMapEnd($params)
    {
        $this->dispatchMapEvent('onEndMapEnd', $params);
    }

    /**
     * Dispatch map event.
     *
     * @param $eventName
     * @param $params
     */
    protected function dispatchMapEvent($eventName, $params)
    {
        $map = $this->mapStorage->getMap($params['map']['uid']);

        $this->dispatch(
            $eventName,
            [
                $params['count'],
                isset($params['time']) ? $params['time'] : time(),
                isset($params['restarted']) ? $params['restarted'] : false,
                $map,
            ]
        );
    }
}
