<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.06
 */

namespace eXpansion\Bundle\Dedimania\Services;


use eXpansion\Bundle\Dedimania\Classes\IXR_Base64;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Framework\Core\Services\Application\Dispatcher;

class DedimaniaService
{
    /**
     * @var DedimaniaRecord[]
     */
    protected $dedimaniaRecords = [];
    protected $serverMaxRank = 15;
    /** @var DedimaniaPlayer[] */
    protected $players = [];
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /** @var DedimaniaRecord[] */
    private $recordsByLogin;

    /** @var int[] */
    private $ranksByLogin;


    private $VReplay = "";
    private $GReplay = "";

    /**
     * DedimaniaService constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return DedimaniaRecord[]
     */
    public function getDedimaniaRecords(): array
    {
        return $this->recordsByLogin;
    }

    /**
     * @param DedimaniaRecord[] $dedimaniaRecords
     */
    public function setDedimaniaRecords($dedimaniaRecords)
    {
        $this->recordsByLogin = [];
        $this->ranksByLogin = [];

        foreach ($dedimaniaRecords as $record) {
            $this->recordsByLogin[$record->login] = $record;
            $this->ranksByLogin[$record->login] = intval($record->rank);
        }


        $this->dispatcher->dispatch('expansion.dedimania.records.loaded', [$this->dedimaniaRecords]);
    }


    /**
     * Check and Add a new record, if needed
     *
     * @param string $login
     * @param int    $score
     * @param int[]  $checkpoints
     *
     * @return int rank, -1 for fail
     */
    public function processRecord($login, $score, $checkpoints)
    {
        $tempRecords = $this->recordsByLogin;
        $tempPositions = $this->ranksByLogin;

        $record = isset($this->recordsByLogin[$login]) ? $this->recordsByLogin[$login] : new DedimaniaRecord($login);
        $tempRecords[$login] = $record;
        $oldRecord = clone $record;

        // if better time

        if ($score < $oldRecord->best || $oldRecord->best == -1) {

            $record->best = $score;
            $record->checks = implode(",", $checkpoints);
            $tempRecords[$login] = $record;

            // sort and update new rank
            uasort($tempRecords, [$this, "compare"]);

            if (!isset($this->players[$record->login])) {
                return -1;
            }

            $player = $this->players[$record->login];

            // recalculate ranks for records
            $rank = 1;
            $newRecord = false;
            foreach ($tempRecords as $login => $tempRecord) {
                $tempRecords[$login]->rank = $rank;
                $tempPositions[$tempRecord->login] = $rank;

                if ($tempRecord->login == $login && ($rank <= $this->serverMaxRank || $rank <= $player->maxRank) && $rank < 100) {
                    $newRecord = $tempRecords[$login];
                }

                $rank++;
            }

            print_r($tempRecords);

            $tempRecords = array_slice($tempRecords, 0, 100, true);
            $tempPositions = array_slice($tempPositions, 0, 100, true);

            if ($newRecord) {
                $this->recordsByLogin = $tempRecords;
                $this->ranksByLogin = $tempPositions;
                $params = [
                    $newRecord,
                    $oldRecord,
                    $tempRecords,
                    $newRecord->rank,
                    $oldRecord->rank,
                ];

                $this->dispatcher->dispatch("expansion.dedimania.records.update", [$params]);

                return $newRecord->rank;
            }
        }

        return -1;
    }

    /**
     * @param DedimaniaRecord $a
     * @param DedimaniaRecord $b
     * @return int
     */
    public function compare($a, $b)
    {
        if ($a->best == $b->best) {
            return 0;
        }

        return ($a->best > $b->best) ? 1 : -1;
    }


    /**
     * @return mixed
     */
    public function getServerMaxRank()
    {
        return $this->serverMaxRank;
    }

    /**
     * @param mixed $serverMaxRank
     */
    public function setServerMaxRank($serverMaxRank)
    {
        $this->serverMaxRank = $serverMaxRank;
    }

    /**
     * @return DedimaniaPlayer[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @param DedimaniaPlayer[] $players
     */
    public function setPlayers(array $players)
    {
        foreach ($players as $player) {
            $this->connectPlayer($player);
        }
    }

    /** @param DedimaniaPlayer $player */
    public function connectPlayer($player)
    {

        $this->players[$player->login] = $player;
        print_r($player);

        $this->dispatcher->dispatch("expansion.dedimania.player.connect", [$player]);


    }

    /**
     * @return string
     */
    public function getVReplay()
    {
        return $this->VReplay;
    }

    /**
     * @param string $VReplay
     */
    public function setVReplay($VReplay)
    {
        $this->VReplay = $VReplay;
    }

    /**
     * @return string
     */
    public function getGReplay()
    {
        return $this->GReplay;
    }

    /**
     * @param string $GReplay
     */
    public function setGReplay($GReplay)
    {
        $this->GReplay = $GReplay;
    }


}