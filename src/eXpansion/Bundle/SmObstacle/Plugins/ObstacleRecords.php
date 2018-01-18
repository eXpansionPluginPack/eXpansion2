<?php

namespace eXpansion\Bundle\SmObstacle\Plugins;

use eXpansion\Bundle\LocalRecords\Services\RecordHandlerFactory;
use eXpansion\Bundle\SmObstacle\DataProviders\Listener\ListenerInterfaceSmObstaclePlayer;
use eXpansion\Bundle\SmObstacle\Structures\ObstacleRun;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\GameTrackmania\DataProviders\Listener\ListenerInterfaceRaceData;
use Maniaplanet\DedicatedServer\Structures\Map;
use Psr\Log\LoggerInterface;

/**
 * Class ObstacleRecords
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  reaby
 */
class ObstacleRecords implements StatusAwarePluginInterface, ListenerInterfaceSmObstaclePlayer, ListenerInterfaceMpLegacyMap, ListenerInterfaceMpLegacyPlayer
{

    /** @var  RecordHandler */
    protected $recordsHandler;

    /** @var Group */
    protected $allPlayersGroup;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var string */
    protected $eventPrefix;

    /** @var DispatcherInterface */
    protected $dispatcher;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * BaseRecords constructor.
     *
     * @param RecordHandlerFactory $recordsHandlerFactory
     * @param Group                $allPlayersGroup
     * @param MapStorage           $mapStorage
     * @param DispatcherInterface  $dispatcher
     * @param LoggerInterface      $logger
     * @param                      $eventPrefix
     */
    public function __construct(
        RecordHandlerFactory $recordsHandlerFactory,
        Group $allPlayersGroup,
        MapStorage $mapStorage,
        DispatcherInterface $dispatcher,
        LoggerInterface $logger,
        $eventPrefix
    ) {
        $this->recordsHandler = $recordsHandlerFactory->create();
        $this->allPlayersGroup = $allPlayersGroup;
        $this->mapStorage = $mapStorage;
        $this->eventPrefix = $eventPrefix;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    public function onPlayerFinish($login, ObstacleRun $run)
    {
        $eventData = $this->recordsHandler->addRecord($login, $run->lastRun, $run->lastCpTimes);
        if ($eventData) {
            $this->dispatchEvent($eventData);
        }
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {
        // Load firs X records for this map.
        $this->recordsHandler->loadForMap($map->uId, 1);

        // Load time information for remaining players.
        $this->recordsHandler->loadForPlayers($map->uId, 1, $this->allPlayersGroup->getLogins());

        // Let others know that records information is now available.
        $this->dispatchEvent(['event' => 'loaded', 'records' => $this->recordsHandler->getRecords()]);
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map)
    {
        $this->recordsHandler->save();
    }

    public function dispatchEvent($eventData)
    {
        $event = $this->eventPrefix.'.'.$eventData['event'];
        unset($eventData['event']);

        $this->dispatcher->dispatch($event, [$eventData]);
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $this->recordsHandler->loadForPlayers($this->mapStorage->getCurrentMap()->uId, [1], [$player->getLogin()]);
    }


    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        if ($status) {
            $map = $this->mapStorage->getCurrentMap();
            $this->onBeginMap($map);
        }
    }

    /**
     * @param Player $player
     * @param string $disconnectionReason
     * @return void
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        //do nothing
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // do nothing
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // do nothing
    }
}
