<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameShootmania\DataProviders\Listener\ListenerInterfaceSmPlayer;
use eXpansion\Framework\GameShootmania\DataProviders\Listener\ListenerInterfaceSmPlayerExtra;
use eXpansion\Framework\GameShootmania\DataProviders\Listener\ListenerInterfaceSmPlayerShoot;
use eXpansion\Framework\GameShootmania\Structures\Landmark;
use eXpansion\Framework\GameShootmania\Structures\Position;

class SmTest implements ListenerInterfaceSmPlayer, ListenerInterfaceSmPlayerShoot, ListenerInterfaceSmPlayerExtra
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
     * @param string   $shooterLogin    login
     * @param string   $victimLogin     login
     * @param int      $weapon          id of weapon: [1-laser, 2-rocket, 3-nucleus, 5-arrow]
     * @param int      $damage          amount damage done by hit
     * @param int      $points          amount of points scored by shooter
     * @param float    $distance        distance between 2 players
     * @param Position $shooterPosition position at level
     * @param Position $victimPosition  position at level
     * @return void
     */
    public function onPlayerHit(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $points,
        $distance,
        Position $shooterPosition,
        Position $victimPosition
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
        Position $shooterPosition,
        Position $victimPosition
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
        $this->console->writeln('Player Triggers Sector: '.$login." Sector:".$sectorId);
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

    /**
     * @param string   $shooterLogin    Login of the player who shot
     * @param string   $victimLogin     Login of the player who dodged
     * @param int      $weapon          Id of the weapon [1-Laser, 2-Rocket, 3-Nucleus, 5-Arrow]
     * @param float    $distance        Distance of the near miss
     * @param Position $shooterPosition position in level
     * @param Position $victimPosition  position in level
     * @return void
     */
    public function onNearMiss(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $distance,
        Position $shooterPosition,
        Position $victimPosition
    ) {
        $this->console->writeln('NearMiss: '.$victimLogin.' Weapon: '.$weapon." Distance:".$distance);
    }

    /**
     * @param string $shooterLogin
     * @param string $victimLogin
     * @param int    $shooterWeapon
     * @param int    $victimWeapon
     * @return void
     */
    public function onShotDeny($shooterLogin, $victimLogin, $shooterWeapon, $victimWeapon)
    {
        $this->console->writeln('Deny: '.$shooterLogin." -> ".$victimLogin);
    }

    /**
     * @param string $login
     * @return void
     */
    public function onFallDamage($login)
    {
        $this->console->writeln('FallDamage: '.$login);
    }

    /**
     * @param string $login
     * @return void
     */
    public function onRequestRespawn($login)
    {
        $this->console->writeln('Respawn: '.$login);
    }
}
