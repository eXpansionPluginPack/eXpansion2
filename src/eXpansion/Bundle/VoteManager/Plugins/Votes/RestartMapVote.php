<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Votes;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;

/**
 * Class NextMapVote
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\VoteManager\Plugins\Votes
 */
class RestartMapVote extends AbstractVotePlugin
{
    /** @var JukeboxService */
    protected $jukebox;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * RestartMapVote constructor.
     *
     * @param PlayerStorage $playerStorage
     * @param JukeboxService $jukebox
     * @param MapStorage $mapStorage
     * @param ChatNotification $chatNotification
     * @param int $duration
     * @param float $ratio
     */
    public function __construct(
        PlayerStorage $playerStorage,
        JukeboxService $jukebox,
        MapStorage $mapStorage,
        ChatNotification $chatNotification,
        int $duration,
        float $ratio
    ) {
        parent::__construct($playerStorage, $duration, $ratio);

        $this->jukebox = $jukebox;
        $this->mapStorage = $mapStorage;
        $this->chatNotification = $chatNotification;
    }

    /**
     * @inheritdoc
     */
    public function getQuestion(): string
    {
        return 'expansion_votemanager.restartmap.question';
    }

    /**
     * @inheritdoc
     */
    public function executeVotePassed()
    {
        $this->jukebox->addMap($this->mapStorage->getCurrentMap(), $this->getCurrentVote()->getPlayer()->getLogin(),
            true, true);
        $this->chatNotification->sendMessage("|info| Vote passed. Will replay map!");
    }

    /**
     * @inheritdoc
     */
    protected function executeVoteFailed()
    {
        // Do Nothing
    }

    /**
     * Get type code of this vote.
     *
     * @return string
     */
    public function getCode(): string
    {
        return 'Exp_RestartMap';
    }

    /**
     * Get native votes this votes replaces.
     *
     * @return string[]
     */
    public function getReplacementTypes(): array
    {
        return ['RestartMap'];
    }
}
