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
class MatchDataProvider extends AbstractDataProvider
{

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param array $params
     */
    public function onStartMatchStart($params)
    {
        $this->dispatch('onStartMatchStart', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param array $params
     */
    public function onStartMatchEnd($params)
    {
        $this->dispatch('onStartMatchEnd', [$params['count'], $params['time']]);
    }
}
