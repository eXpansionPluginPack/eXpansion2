<?php

namespace eXpansion\Framework\GameShootmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class PlayerDataProvider provides information to plugins about what is going on with players.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class PlayerActionDataProvider extends AbstractDataProvider
{

    /**
     * @param $params
     * @return void
     */
    public function onActionCustomEvent($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['shooter'],
            $params['victim'],
            $params['actionId'],
            $params['param1'],
            $params['param2'],
        ]);
    }


    /**
     * @param $params
     * @return void
     */
    public function onActionEvent($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['login'],
            $params['actioninput'],
        ]);
    }


    /**
     *
     * @param $params
     */
    public function onPlayerRequestActionChange($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['login'],
            $params['actionchange'],
        ]);
    }

}
