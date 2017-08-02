<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\MapStorage;

/**
 * Class MatchDataProvider
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class PodiumDataProvider extends AbstractDataProvider
{

    /**
     * Callback sent when the "Maniaplanet.Podium_Start" section start.
     * XMLRPC Api Version >=2.0.0:
     * @param array $params
     */
    public function onPodiumStart($params)
    {
        $this->dispatch('onPodiumStart', [$params['time']]);
    }

    /**
     * Callback sent when the "Maniaplanet.Podium_End" section end.
     * XMLRPC Api Version >=2.0.0:
     * @param array $params
     */
    public function onPodiumEnd($params)
    {
        $this->dispatch('onPodiumEnd', [$params['time']]);
    }

}
