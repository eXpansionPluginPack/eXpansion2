<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Votes;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class NextMapVote
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\VoteManager\Plugins\Votes
 */
class NextMapVote extends AbstractVotePlugin
{
    /** @var Connection */
    protected $connection;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * NextMapVote constructor.
     *
     * @param PlayerStorage $playerStorage
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param int $duration
     * @param float $ratio
     */
    public function __construct(
        PlayerStorage $playerStorage,
        Connection $connection,
        ChatNotification $chatNotification,
        int $duration,
        float $ratio
    ) {
        parent::__construct($playerStorage, $duration, $ratio);

        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
    }


    /**
     * @inheritdoc
     */
    protected function getQuestion(): string
    {
        return 'expansion_votemanager.nextmap.question';
    }

    /**
     * @inheritdoc
     */
    protected function executeVotePassed()
    {
        $this->connection->nextMap(false);
        $this->chatNotification->sendMessage("|info| Vote passed. Skipping map!");
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
       return 'Exp_NextMap';
    }

    /**
     * Get native votes this votes replaces.
     *
     * @return string[]
     */
    public function getReplacementTypes(): array
    {
        return ['NextMap'];
    }
}
