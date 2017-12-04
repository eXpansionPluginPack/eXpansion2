<?php

namespace eXpansion\Bundle\VoteManager\Structures;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class NexMapVote
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\VoteManager\Structures
 */
class NextMapVote extends AbstractVote
{
    /** @var Connection */
    protected $connection;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * NexMapVote constructor.
     *
     * @param Player $player
     * @param string $type
     * @param int $duration
     * @param float $ration
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     */
    public function __construct(
        Player $player,
        string $type,
        int $duration = 30,
        float $ration = 0.57,
        Connection $connection,
        ChatNotification $chatNotification
    ) {
        parent::__construct($player, $type, $duration, $ration);

        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
    }


    /**
     * @inheritdoc
     */
    public function getQuestion(): string
    {
        return 'expansion_votemanager.nextmap.question';
    }

    /**
     * @inheritdoc
     */
    public function executeVotePassed()
    {
        $this->connection->nextMap(false);
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
