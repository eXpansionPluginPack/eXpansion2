<?php

namespace eXpansion\Framework\PlayersBundle\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use \eXpansion\Framework\Core\Storage\Data\Player as PlayerData;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use eXpansion\Framework\GameManiaplanet\ScriptMethods\GetScores;
use eXpansion\Framework\PlayersBundle\Model\Map\PlayerTableMap;
use eXpansion\Framework\PlayersBundle\Model\Player as PlayerModel;
use eXpansion\Framework\PlayersBundle\Model\PlayerQuery;
use Propel\Runtime\Propel;


/**
 * Class Player
 *
 * @package eXpansion\Framework\PlayersBundle\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Player implements ListenerInterfaceMpLegacyPlayer, ListenerInterfaceMpScriptMatch, StatusAwarePluginInterface
{
    /** @var GetScores  */
    protected $getScores;

    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var PlayerModel[] */
    protected $loggedInPlayers = [];

    /** @var int[] Timestamp at which player play time was last updated in DB. */
    protected $playerLastUpTime = [];

    /**
     * Player constructor.
     *
     * @param GetScores $getScores
     * @param PlayerStorage $playerStorage
     */
    public function __construct(
        GetScores $getScores,
        PlayerStorage $playerStorage
    ) {
        $this->getScores = $getScores;
        $this->playerStorage = $playerStorage;
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        if ($status) {
            foreach ($this->playerStorage->getOnline() as $player) {
                $this->onPlayerConnect($player);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(PlayerData $playerData)
    {
        $playerQuery = PlayerQuery::create();
        $player = $playerQuery->findOneByLogin($playerData->getLogin());
        $update = false;

        if (is_null($player)) {
            $player = new PlayerModel();
            $player->setLogin($playerData->getLogin());
            $update = true;
        }
        $player->setNickname($playerData->getNickName());
        $player->setNicknameStripped(TMString::trimStyles($playerData->getNickName()));
        $player->setPath($playerData->getPath());

        $this->loggedInPlayers[$player->getLogin()] = $player;
        $this->playerLastUpTime[$player->getLogin()] = time();
        $player->setLastOnline(new \DateTime('now'));

        if ($update) {
            $this->updatePlayer($player);
            $player->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(PlayerData $player, $disconnectionReason)
    {
        $playerModel = $this->getPlayer($player->getLogin());
        $this->updatePlayer($playerModel);
        $playerModel->save();

        unset($this->playerLastUpTime[$player->getLogin()]);
        unset($this->loggedInPlayers[$player->getLogin()]);
    }

    /**
     * @inheritdoc
     */
    public function onEndMatchEnd($count, $time)
    {
        $object = $this;
        $this->getScores->get(function($scores) use($object) {

            var_dump($scores);

            $object->updateWithScores($scores);
        });
    }

    /**
     * Update when scores is available.
     *
     * @param $scores
     */
    public function updateWithScores($scores)
    {
        $con = Propel::getWriteConnection(PlayerTableMap::DATABASE_NAME);
        $con->beginTransaction();

        // Update the winner player.
        $player = $this->getPlayer($scores['winnerplayer']);
        if ($player) {
            $player->setWins($player->getWins() + 1);
            $this->updatePlayer($player);
            $player->save();
        }

        // Update remaining players.
        foreach ($this->loggedInPlayers as $player) {
            $this->updatePlayer($player);
            $player->save();
        }

        $con->commit();
    }

    /**
     * Update player information.
     *
     * @param PlayerModel $player Login of the player.
     */
    protected function updatePlayer($player)
    {
        $time = time();
        $upTime = $time - $this->playerLastUpTime[$player->getLogin()];
        $this->playerLastUpTime[$player->getLogin()] = $time;

        $player->setOnlineTime($player->getOnlineTime() + $upTime);
    }

    /**
     * Get data on a player.
     *
     * @param string $login Login of the player.
     *
     * @return PlayerModel
     */
    public function getPlayer($login)
    {
        if (isset($this->loggedInPlayers[$login])) {
            return $this->loggedInPlayers[$login];
        }

        return (new PlayerQuery())->findOneByLogin($login);
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(PlayerData $oldPlayer, PlayerData $player)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(PlayerData $oldPlayer, PlayerData $player)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onStartMatchStart($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onStartMatchEnd($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onEndMatchStart($count, $time)
    {
        // Nothing to do here.
    }


    /**
     * @inheritdoc
     */
    public function onStartTurnStart($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onStartTurnEnd($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onEndTurnStart($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onEndTurnEnd($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onStartRoundStart($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onStartRoundEnd($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onEndRoundStart($count, $time)
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function onEndRoundEnd($count, $time)
    {
        // Nothing to do here.
    }
}
