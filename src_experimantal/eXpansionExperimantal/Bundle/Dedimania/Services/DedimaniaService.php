<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.06
 */

namespace eXpansionExperimantal\Bundle\Dedimania\Services;


use eXpansionExperimantal\Bundle\Dedimania\Classes\IXR_Base64;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\PlayerStorage;

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

    /** @var PlayerStorage */
    private $playerStorage;

    /** @var string */
    private $GReplayOwner;

    private $disabled = false;

    /**
     * DedimaniaService constructor.
     * @param Dispatcher    $dispatcher
     * @param PlayerStorage $playerStorage
     */
    public function __construct(
        Dispatcher $dispatcher,
        PlayerStorage $playerStorage
    ) {
        $this->dispatcher = $dispatcher;
        $this->playerStorage = $playerStorage;
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
        $this->setDisabled(false);
        $this->recordsByLogin = [];
        $this->ranksByLogin = [];
        $records = [];
        foreach ($dedimaniaRecords as $record) {
            $this->recordsByLogin[$record->login] = $record;
            $this->ranksByLogin[$record->login] = intval($record->rank);
            $records[] = $record;
        }

        $this->dispatcher->dispatch('expansion.dedimania.records.load', [$records]);
    }

    public function getRecord($login)
    {
        if (isset($this->recordsByLogin[$login])) {
            return $this->recordsByLogin[$login];
        }

        return null;
    }


    /**
     * Check and Add a new record, if needed
     *
     * @param string $login
     * @param int    $score
     * @param int[]  $checkpoints
     *
     * @return DedimaniaRecord|false
     */
    public function processRecord($login, $score, $checkpoints)
    {
        if ($this->isDisabled()) {
            echo "disabled state.\n";

            return false;
        }

        $tempRecords = $this->recordsByLogin;
        $tempPositions = $this->ranksByLogin;

        if (isset($this->recordsByLogin[$login])) {
            $record = $this->recordsByLogin[$login];
        } else {
            $record = new DedimaniaRecord($login);
            $pla = $this->playerStorage->getPlayerInfo($login);
            $record->nickName = $pla->getNickName();
        }

        $tempRecords[$login] = clone $record;
        $oldRecord = clone $record;

        // if better time
        if ($score < $oldRecord->best || $oldRecord->best == -1) {
            $record->best = $score;
            $record->checks = implode(",", $checkpoints);
            $tempRecords[$login] = $record;

            // sort and update new rank
            uasort($tempRecords, [$this, "compare"]);

            if (!isset($this->players[$login])) {
                echo "player $login not connected\n";

                return false;
            }

            $player = $this->players[$login];

            // recalculate ranks for records
            $rank = 1;
            $newRecord = false;
            foreach ($tempRecords as $key => $tempRecord) {
                $tempRecords[$key]->rank = $rank;
                $tempPositions[$key] = $rank;

                if ($tempRecord->login == $login && ($rank <= $this->serverMaxRank || $rank <= $player->maxRank) && $rank < 100) {
                    $newRecord = $tempRecords[$login];
                }

                $rank++;
            }


            $tempRecords = array_slice($tempRecords, 0, 100, true);
            $tempPositions = array_slice($tempPositions, 0, 100, true);

            if ($newRecord) {
                $this->recordsByLogin = $tempRecords;
                $outRecords = usort($tempRecords, [$this, 'compare']);

                $this->ranksByLogin = $tempPositions;
                $params = [
                    $newRecord,
                    $oldRecord,
                    $outRecords,
                    $newRecord->rank,
                    $oldRecord->rank,
                ];

                $this->dispatcher->dispatch("expansion.dedimania.records.update", [$params]);

                return $newRecord;
            }
        }

        return false;
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
        $this->dispatcher->dispatch("expansion.dedimania.player.connect", [$player]);
    }

    /**
     * @param $login
     */
    public function disconnectPlayer($login)
    {
        if (isset($this->players[$login])) {
            $this->dispatcher->dispatch("expansion.dedimania.player.disconnect", [clone $this->players[$login]]);
            unset($this->players[$login]);
        }
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
     * @return array
     */
    public function getGReplay()
    {
        return [$this->GReplayOwner, $this->GReplay];
    }

    /**
     * @param string     $login
     * @param IXR_Base64 $GReplay
     */
    public function setGReplay($login, $GReplay)
    {
        $this->GReplayOwner = $login;
        $this->GReplay = $GReplay;
    }

    /**
     * @return string|false
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param string|false $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }


}