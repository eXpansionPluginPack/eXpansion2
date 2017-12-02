<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class MatchDataProvider
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ScriptMatchDataProvider extends AbstractDataProvider
{

    /**
     * Callback sent when the "StartMatch" section start.
     * XMLRPC Api Version >=2.0.0:
     * @param array $params
     */
    public function onStartMatchStart($params)
    {
        $this->dispatch('onStartMatchStart', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartMatch" section end.
     * XMLRPC Api Version >=2.0.0:
     * @param array $params
     */
    public function onStartMatchEnd($params)
    {
        $this->dispatch('onStartMatchEnd', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartMatch" section start.
     * XMLRPC Api Version >=2.0.0:
     * @param array $params
     */
    public function onEndMatchStart($params)
    {
        $this->dispatch('onEndMatchStart', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartMatch" section end.
     * XMLRPC Api Version >=2.0.0:
     * @param array $params
     */
    public function onEndMatchEnd($params)
    {
        $this->dispatch('onEndMatchEnd', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartRound" section start.
     * XMLRPC Api Version >=2.0.0:
     * @param $params
     */
    public function onStartRoundStart($params)
    {
        $this->dispatch('onStartRoundStart', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartRound" section end.
     * XMLRPC Api Version >=2.0.0:
     * @param $params
     */
    public function onStartRoundEnd($params)
    {
        $this->dispatch('onStartRoundEnd', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartRound" section start.
     * XMLRPC Api Version >=2.0.0:
     * @param $params
     */
    public function onEndRoundStart($params)
    {
        $this->dispatch('onEndRoundStart', [$params['count'], $params['time']]);
    }

    /**
     * Callback sent when the "StartRound" section end.
     * XMLRPC Api Version >=2.0.0:
     * @param $params
     */
    public function onEndRoundEnd($params)
    {
        $this->dispatch('onEndRoundEnd', [$params['count'], $params['time']]);
    }

    /**
     * Description: Callback sent when the "StartTurn" section start.
     * Version >=2.0.0:
     * @param $params
     */

    public function onStartTurnStart($params)
    {
        $this->dispatch('onStartTurnStart', [$params['count'], $params['time']]);
    }

    /**
     * Description: Callback sent when the "StartTurn" section ends.
     * Version >=2.0.0:
     * @param $params
     */

    public function onStartTurnEnd($params)
    {
        $this->dispatch('onStartTurnEnd', [$params['count'], $params['time']]);
    }

    /**
     * Description: Callback sent when the "EndTurn" section start.
     * Version >=2.0.0:
     * @param $params
     */

    public function onEndTurnStart($params)
    {
        $this->dispatch('onEndTurnStart', [$params['count'], $params['time']]);
    }

    /**
     * Description: Callback sent when the "onEndTurn" section ends.
     * Version >=2.0.0:
     * @param $params
     */

    public function onEndTurnEnd($params)
    {
        $this->dispatch('onEndTurnEnd', [$params['count'], $params['time']]);
    }


}
