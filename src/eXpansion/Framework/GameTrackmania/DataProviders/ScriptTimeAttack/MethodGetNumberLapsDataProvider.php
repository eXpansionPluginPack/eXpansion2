<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\ScriptTimeAttack;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\DataProviders\MethodScriptDataProviderInterface;

/**
 * Class MethodGetNumberLapsDataProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\GameTrackmania\DataProviders
 */
class MethodGetNumberLapsDataProvider extends AbstractDataProvider implements MethodScriptDataProviderInterface
{

    /**
     * Request call to fetch something..
     *
     * @return void
     */
    public function request()
    {
        $this->dispatch('set', [1]);
    }
}