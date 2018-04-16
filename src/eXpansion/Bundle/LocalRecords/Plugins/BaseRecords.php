<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Bundle\LocalRecords\Services\RecordHandlerFactory;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use eXpansion\Framework\GameTrackmania\ScriptMethods\GetNumberOfLaps;
use Maniaplanet\DedicatedServer\Structures\Map;
use Psr\Log\LoggerInterface;

/**
 * Class RaceRecords
 *
 * ADD status aware interface and load on activation.
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class BaseRecords implements ListenerInterfaceMpScriptMap, ListenerInterfaceMpScriptMatch, ListenerInterfaceMpLegacyPlayer, StatusAwarePluginInterface
{
    /** @var  RecordHandler */
    protected $recordsHandler;

    /** @var Group */
    protected $allPlayersGroup;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var string */
    protected $eventPrefix;

    /** @var GetNumberOfLaps */
    protected $getNumberOfLaps;

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
     * @param GetNumberOfLaps      $getNumberOfLaps
     * @param LoggerInterface      $logger
     * @param                      $eventPrefix
     */
    public function __construct(
        RecordHandlerFactory $recordsHandlerFactory,
        Group $allPlayersGroup,
        MapStorage $mapStorage,
        DispatcherInterface $dispatcher,
        GetNumberOfLaps $getNumberOfLaps,
        LoggerInterface $logger,
        $eventPrefix
    ) {
        $this->recordsHandler = $recordsHandlerFactory->create();
        $this->allPlayersGroup = $allPlayersGroup;
        $this->mapStorage = $mapStorage;
        $this->eventPrefix = $eventPrefix;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->getNumberOfLaps = $getNumberOfLaps;
    }

    /**
     * Get the current record handler.
     *
     * @return RecordHandler
     */
    public function getRecordsHandler()
    {
        return $this->recordsHandler;
    }


    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function setStatus($status)
    {
        if ($status) {
            $map = $this->mapStorage->getCurrentMap();
            $this->onStartMapStart(0, 0, 0, $map);
        }
    }

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        $plugin = $this;

        $this->getNumberOfLaps->get(function($laps) use ($map, $plugin) {
            $plugin->startMap($map, $laps);
        });
    }

    /**
     * Start plugin for a certain map.
     *
     * @param Map $map
     * @param int $nbLaps
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function startMap($map, $nbLaps)
    {
        // Load firs X records for this map.
        $this->recordsHandler->loadForMap($map->uId, $nbLaps);

        // Load time information for remaining players.
        $this->recordsHandler->loadForPlayers($map->uId, $nbLaps, $this->allPlayersGroup->getLogins());

        // Let others know that records information is now available.
        $this->dispatchEvent(['event' => 'loaded', 'records' => $this->recordsHandler->getRecords()]);
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $this->recordsHandler->loadForPlayers($this->mapStorage->getCurrentMap()->uId, [1], [$player->getLogin()]);
    }

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {
        // Nothing to do
    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchStart($count, $time)
    {
        // Nothing to do.
    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchEnd($count, $time)
    {
        $this->recordsHandler->save();
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {
        // Nothing to do.
    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {
        // Nothing to do.
    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // Nothing to do.
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // Nothing to do.
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // Nothing to do.
    }

    /**
     * Dispatch a record event.
     *
     * @param $eventData
     */
    public function dispatchEvent($eventData)
    {
        $event = $this->eventPrefix.'.'.$eventData['event'];
        unset($eventData['event']);

        $eventData[RecordHandler::COL_PLUGIN] = $this;
        $this->dispatcher->dispatch($event, [$eventData]);
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndMatchStart($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndMatchEnd($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartTurnStart($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartTurnEnd($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndTurnStart($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndTurnEnd($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartRoundStart($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartRoundEnd($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndRoundStart($count, $time)
    {
        // Nothing
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndRoundEnd($count, $time)
    {
        // Nothing
    }
}
