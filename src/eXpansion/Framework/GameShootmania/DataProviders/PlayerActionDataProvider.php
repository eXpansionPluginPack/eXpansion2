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
     * @param int    $time
     * @param string $shooterLogin
     * @param string $victimLogin
     * @param string $actionId
     * @param mixed  $param1
     * @param mixed  $param2
     * @return void
     */
    public function onActionCustomEvent(
        $time,
        $shooterLogin,
        $victimLogin,
        $actionId,
        $param1,
        $param2
    ) {
        $this->dispatch(__FUNCTION__, [
            $shooterLogin,
            $victimLogin,
            $actionId,
            $param1,
            $param2,
        ]);
    }


    /**
     * @param int    $time
     * @param string $login
     * @param mixed  $actionInput
     * @return void
     */
    public function onActionEvent(
        $time,
        $login,
        $actionInput

    ) {
        $this->dispatch(__FUNCTION__, [
            $login,
            $actionInput,
        ]);
    }


    /**
     *
     * @param int    $time
     * @param string $login
     * @param string $actionChange
     */
    public function onPlayerRequestActionChange(
        $time,
        $login,
        $actionChange
    ) {
        $this->dispatch(__FUNCTION__, [
            $login,
            $actionChange,
        ]);
    }

    /**
     *
     * @param int    $time
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     * @return void
     */
    public function onPlayerThrowsObject(
        $time,
        $login,
        $objectId,
        $modelId,
        $modelName
    ) {
        $this->dispatch(__FUNCTION__, [
            $login,
            $objectId,
            $modelId,
            $modelName,
        ]);
    }

}
