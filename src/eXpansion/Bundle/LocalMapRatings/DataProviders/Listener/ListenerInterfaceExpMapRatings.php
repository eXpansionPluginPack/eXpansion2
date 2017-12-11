<?php

namespace eXpansion\Bundle\LocalMapRatings\DataProviders\Listener;

use eXpansion\Bundle\LocalMapRatings\Model\Maprating;

/**
 * Interface
 *
 * @package eXpansion\Bundle\LocalRecords\DataProviders\Listener;
 * @author  reaby
 */
interface ListenerInterfaceExpMapRatings
{
    /**
     * Called when map ratings are loaded.
     *
     * @param Maprating[] $ratings
     * @return void
     */
    public function onMapRatingsLoaded($ratings);

    /**
     * Called when map ratings are changed.
     *
     * @param string      $login
     * @param int         $score
     * @param Maprating[] $ratings
     * @return void
     */
    public function onMapRatingsChanged($login, $score, $ratings);


}
