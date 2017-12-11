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
    public function onMapRatingsChanged($login, $score, $ratings)
    {
        $this->dispatch('onMapRatingsChanged', [$login, $score, $ratings]);
    }

    public function onMapRatingsLoaded($params)
    {
        $this->dispatch('onMapRatingsLoaded', [$params]);
    }

}
