<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * VoteDataProvider provides vote information to plugins.
 *
 * @package eXpansion\Framework\Core\DataProviders
 * @author reaby
 */
class EchoDataProvider extends AbstractDataProvider
{

    public function onEcho($internal, $public)
    {
        $this->dispatch(__FUNCTION__, [$internal, $public]);
    }
}
