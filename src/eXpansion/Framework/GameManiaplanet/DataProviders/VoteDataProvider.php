<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\PlayerStorage;

/**
 * VoteDataProvider provides vote information to plugins.
 *
 * @package eXpansion\Framework\Core\DataProviders
 * @author reaby
 */
class VoteDataProvider extends AbstractDataProvider
{

    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    public function __construct(PlayerStorage $playerStorage)
    {
        $this->playerStorage = $playerStorage;
    }

    public function VoteUpdated($stateName, $login, $cmdName, $cmdValue)
    {
        switch ($stateName) {
            case "NewVote":
                $this->dispatch("onVoteNew", [$this->playerStorage->getPlayerInfo($login), $cmdName, $cmdValue]);
                break;
            case "VoteCancelled":
                $this->dispatch("onVoteCancelled", [$this->playerStorage->getPlayerInfo($login), $cmdName, $cmdValue]);
                break;
            case "VotePassed":
                $this->dispatch("onVotePassed", [$this->playerStorage->getPlayerInfo($login), $cmdName, $cmdValue]);
                break;
            case "VoteFailed":
                $this->dispatch("onVoteFailed", [$this->playerStorage->getPlayerInfo($login), $cmdName, $cmdValue]);
                break;
        }
    }
}
