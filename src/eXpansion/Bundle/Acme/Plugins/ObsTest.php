<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Bundle\SmObstacle\DataProviders\Listener\ListenerInterfaceSmObstaclePlayer;
use eXpansion\Bundle\SmObstacle\Structures\ObstacleRun;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\PlayerStorage;

class ObsTest implements ListenerInterfaceSmObstaclePlayer
{
    /**
     * @var ChatNotification
     */
    private $chatNotification;
    /**
     * @var Console
     */
    private $console;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * Test constructor.
     * @param ChatNotification $chatNotification
     * @param Console          $console
     * @param PlayerStorage    $playerStorage
     */
    public function __construct(
        ChatNotification $chatNotification,
        Console $console,
        PlayerStorage $playerStorage
    ) {

        $this->chatNotification = $chatNotification;
        $this->console = $console;
        $this->playerStorage = $playerStorage;
    }


    public function onPlayerFinish($login, ObstacleRun $run)
    {

    }
}
