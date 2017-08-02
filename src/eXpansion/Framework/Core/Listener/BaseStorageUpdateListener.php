<?php

namespace eXpansion\Framework\Core\Listener;

use eXpansion\Framework\Core\Model\Event\DedicatedEvent;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class BaseStorageUpdateListener
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Listner
 */
class BaseStorageUpdateListener
{
    /** @var Connection */
    protected $connection;

    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var DispatcherInterface */
    protected $dispatcher;

    /**
     * BaseStorageUpdateListener constructor.
     *
     * @param GameDataStorage $gameDataStorage
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        Connection $connection,
        GameDataStorage $gameDataStorage,
        DispatcherInterface $dispatcher
    ) {
        $this->connection = $connection;
        $this->gameDataStorage = $gameDataStorage;
        $this->dispatcher = $dispatcher;

        $gameInfos = $this->connection->getCurrentGameInfo();
        $serverOptions = $this->connection->getServerOptions();
        $this->gameDataStorage->setServerOptions($serverOptions);

        $this->gameDataStorage->setSystemInfo($this->connection->getSystemInfo());
        $this->gameDataStorage->setGameInfos(clone $gameInfos);
        $this->gameDataStorage->setVersion($this->connection->getVersion());
    }

    /**
     *
     */
    public function onManiaplanetGameExpansionBeforeInit()
    {
        // Nothing to do.
    }

    /**
     * Called on the begining of a new map.
     *
     * @param DedicatedEvent $event
     */
    public function onManiaplanetGameBeginMap(DedicatedEvent $event)
    {
        $serverOptions = $this->connection->getServerOptions();
        $this->gameDataStorage->setServerOptions($serverOptions);
        $this->gameDataStorage->setSystemInfo($this->connection->getSystemInfo());

        $newGameInfos = $this->connection->getCurrentGameInfo();
        $prevousGameInfos = $this->gameDataStorage->getGameInfos();

        if ($prevousGameInfos->gameMode != $newGameInfos->gameMode || $prevousGameInfos->scriptName != $newGameInfos->scriptName) {
            // TODO move this logic somewhere else.
            $this->dispatcher->reset();

            $this->gameDataStorage->setGameInfos(clone $newGameInfos);
            // TODO dispatch custom event to let it know.
        }
    }
}
