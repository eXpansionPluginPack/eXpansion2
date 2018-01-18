<?php
/**
 * Created by PhpStorm.
 * User: Petri
 * Date: 18.1.2018
 * Time: 16.57
 */

namespace eXpansion\Bundle\SmObstacle\Structures;


use Maniaplanet\DedicatedServer\Structures\AbstractStructure;

class ObstacleRun extends AbstractStructure
{
    /** @var int  */
    public $time = 0;

    /** @var int  */
    public $respawnCount = 0;

    /** @var int  */
    public $bestRun = 0;

    /** @var int  */
    public $lastRun = 0;

    /** @var string */
    public $lastCheckpointId;

    /** @var bool  */
    public $usedJump;

    /** @var int  */
    public $cpProgress = 0;

    /** @var array  */
    public $lastCpTimes = [];

    /** @var array  */
    public $bestCpTimes = [];

}