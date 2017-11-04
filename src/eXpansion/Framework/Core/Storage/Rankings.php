<?php

namespace eXpansion\Framework\Core\Storage;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\PlayerRanking;
use Maniaplanet\DedicatedServer\Xmlrpc\IndexOutOfBoundException;

/**
 * Class Rankings
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Rankings implements ListenerInterfaceExpTimer
{
    /** @var Connection */
    protected $connection;

    /**
     * Rankings constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get the current rankings.
     *
     * This method will get all current rankings by batch to prevent any connection issues.
     *
     * @TODO this does not work it always retunrs score null and so on.
     *
     * @return PlayerRanking[]
     */
    public function getCurrentRankings()
    {
        if (empty($this->currentRankings)) {
            $chunkSize = 50;
            $offset = 0;
            do {
                try {
                    $rankings = $this->connection->getCurrentRanking($chunkSize, $offset);
                    $offset += $chunkSize;
                    $this->currentRankings = array_merge($this->currentRankings, $rankings);
                } catch (IndexOutOfBoundException $e) {
                    // We are expecting this exception, if we have an empty chunk.
                    $rankings = array();
                }
            } while (!empty($rankings) && count($rankings) == $chunkSize);
        }

        return $this->currentRankings;
    }


    public function onPreLoop()
    {
        // Reset current rankings
        $this->currentRankings = array();
    }

    public function onPostLoop()
    {
        // Nothing to do.
    }

    public function onEverySecond()
    {
        // Nothing to do.
    }
}