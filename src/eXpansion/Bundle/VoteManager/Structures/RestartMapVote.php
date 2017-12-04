<?php

namespace eXpansion\Bundle\VoteManager\Structures;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;

/**
 * Class NexMapVote
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\VoteManager\Structures
 */
class RestartMapVote extends AbstractVote
{
    /** @var JukeboxService */
    protected $jukebox;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * NexMapVote constructor.
     *
     * @param Player $player
     * @param string $type
     * @param int $duration
     * @param float $ration
     * @param JukeboxService $jukebox
     * @param MapStorage $mapStorage
     * @param ChatNotification $chatNotification
     */
    public function __construct(
        Player $player,
        string $type,
        int $duration = 30,
        float $ration = 0.57,
        JukeboxService $jukebox,
        MapStorage $mapStorage,
        ChatNotification $chatNotification
    ) {
        parent::__construct($player, $type, $duration, $ration);

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
        $this->jukebox->addMap($this->mapStorage->getCurrentMap(), $this->getPlayer()->getLogin(), true);
        $this->chatNotification->sendMessage("|info| Vote passed. Skipping map!");
    }

    /**
     * @inheritdoc
     */
    public function executeVoteFailed()
    {
        // Do Nothing
    }
}
