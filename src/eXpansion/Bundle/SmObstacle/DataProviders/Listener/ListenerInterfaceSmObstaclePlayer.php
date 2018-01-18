<?php
/**
 * Created by PhpStorm.
 * User: Petri
 * Date: 18.1.2018
 * Time: 1.21
 */

namespace eXpansion\Bundle\SmObstacle\DataProviders\Listener;


use eXpansion\Bundle\SmObstacle\Structures\ObstacleRun;

interface ListenerInterfaceSmObstaclePlayer
{

    public function onPlayerFinish($login, ObstacleRun $run);

}