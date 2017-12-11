<?php

namespace eXpansion\Bundle\LocalMapRatings\DataProviders\Listener;

use eXpansion\Bundle\LocalMapRatings\Model\Maprating;

/**
 * Interface
 *
 * @package eXpansion\Bundle\LocalRecords\DataProviders\Listener;
 * @author  reaby
 */
interface MapRatingsDataListener
{
    /**
     * Called when map ratings are loaded.
     *
     * @param Maprating[] $ratings
     */
    public function onMapRatingsLoaded($ratings);

    /**
     * Called when map ratings are changed.
     *
     * @param Maprating[] $ratings
     */
    public function onMapRatingsChanged($ratings);


}
