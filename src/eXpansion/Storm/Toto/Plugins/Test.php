<?php

namespace eXpansion\Storm\Toto\Plugins;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameShootmania\DataProviders\Listener\ListenerInterfaceSmPlayer;
use eXpansion\Framework\GameShootmania\DataProviders\Listener\ListenerInterfaceSmPlayerShoot;
use eXpansion\Framework\GameShootmania\Structures\Landmark;

class Test implements ListenerInterfaceSmPlayer, ListenerInterfaceSmPlayerShoot
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

    /**
     * Callback sent when a player is hit.
     *
     * @param string $shooterLogin    login
     * @param string $victimLogin     login
     * @param int    $weapon          id of weapon: [1-laser, 2-rocket, 3-nucleus, 5-arrow]
     * @param int    $damage          amount damage done by hit
     * @param int    $points          amount of points scored by shooter
     * @param float  $distance        distance between 2 players
     * @param array  $shooterPosition position at level
     * @param array  $victimPosition  position at level
     * @return void
     */
    public function onPlayerHit(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $points,
        $distance,
        $shooterPosition,
        $victimPosition
    ) {
        // do nothing
        $this->console->writeln($shooterLogin." -> ".$victimLogin." with: ".$damage);
    }

    /**
     * Callback sent when a player is eliminated.
     * @param string $shooterLogin    login
     * @param string $victimLogin     login
     * @param int    $weapon          id of weapon: [1-laser, 2-rocket, 3-nucleus, 5-arrow]
     * @param int    $damage          amount damage done by hit
     * @param array  $shooterPosition position at level
     * @param array  $victimPosition  position at level
     * @return void
     */
    public function onArmorEmpty(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $shooterPosition,
        $victimPosition
    ) {
        $this->console->writeln("armor empty: ".$victimLogin." with: ".$damage);
    }

    /**
     * Callback when pole is being captured
     *
     * @param array    $players
     * @param Landmark $landmark
     */
    public function onCapture(
        $players,
        Landmark $landmark
    ) {
        $this->console->writeln('onCapture');
    }

    /**
     * Callback when player triggers sector
     *
     * @param string $login
     * @param string $sectorId
     */
    public function onPlayerTriggersSector(
        $login,
        $sectorId
    ) {
        $this->console->writeln('onPlayerTriggersSector: '.$login);
    }

    /**
     *  Callback when player touches an object at level
     *
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     */
    public function onPlayerTouchesObject(
        $login,
        $objectId,
        $modelId,
        $modelName
    ) {
        $this->console->writeln('Object: '.$login.' ModelName:'.$modelName);
    }

    /**
     *  Callback when player touches an object at level
     *
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     */
    public function onPlayerThrowsObject(
        $login,
        $objectId,
        $modelId,
        $modelName
    ) {
        $this->console->writeln('Throw: '.$login.' ModelName:'.$modelName);
    }

    /**
     * @param string $login  login
     * @param int    $weapon indexes are: 1-Laser, 2-Rocket, 3-Nucleus, 5-Arrow
     * @return void
     */
    public function onShoot($login, $weapon)
    {
        $this->console->writeln('Shoot: '.$login.' Weapon: '.$weapon);
    }
}
