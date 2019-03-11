<?php

namespace eXpansion\Bundle\ServerInformation\Services;

use FML\Controls\Control;
use Maniaplanet\DedicatedServer\Structures\Player;

interface ServerInformationInterface
{
    /**
     * Get server information
     *
     * @param string $login
     *
     * @return Control
     */
    public function getInformation(string $login): Control;

    /**
     * Check if particular information can be shown to this particular player.
     *
     * @param string $login
     *
     * @return bool
     */
    public function canShow(string $login): bool;
}