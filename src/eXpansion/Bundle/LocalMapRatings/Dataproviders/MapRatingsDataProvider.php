<?php

namespace eXpansion\Bundle\LocalMapRatings\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class RecordsDataProvider
 *
 * @package eXpansion\Bundle\LocalMapRatings\DataProviders;
 * @author  reaby
 */
class MapRatingsDataProvider extends AbstractDataProvider
{
    public function onMapRatingsChanged($params)
    {
        $this->dispatch('onMapRatingsChanged', [$params]);
    }

    public function onMapRatingsLoaded($params)
    {
        $this->dispatch('onMapRatingsLoaded', [$params]);
    }

}
