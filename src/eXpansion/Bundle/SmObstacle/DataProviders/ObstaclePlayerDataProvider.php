<?php
/**
 * Created by PhpStorm.
 * User: Petri
 * Date: 18.1.2018
 * Time: 1.09
 */

namespace eXpansion\Bundle\SmObstacle\DataProviders;

use eXpansion\Bundle\SmObstacle\Structures\ObstacleRun;
use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\PlayerStorage;

class ObstaclePlayerDataProvider extends AbstractDataProvider
{
     /**
     * @param $params
     * @return void
     */
    public function onPlayerFinish($params)
    {
        $login = $params['Player']['Login'];
        $run = ObstacleRun::fromArray($params['Run']);

        print_r($run);

        $this->dispatch("onPlayerFinish", [$login, $run]);

    }


}