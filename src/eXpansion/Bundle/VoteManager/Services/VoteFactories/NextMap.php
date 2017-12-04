<?php

namespace eXpansion\Bundle\VoteManager\Services\VoteFactories;

use eXpansion\Bundle\LocalRecords\Plugins\ChatNotification;
use eXpansion\Bundle\VoteManager\Structures\AbstractVote;
use eXpansion\Bundle\VoteManager\Structures\NextMapVote;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class RestartMap
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\VoteManager\Services\VoteFactories
 */
class NextMap extends AbstractFactory
{
    /** @var Connection */
    protected $connection;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * NextMap constructor.
     *
     * @param int $duration
     * @param float $ration
     * @param string $class
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     */
    public function __construct(
        int $duration,
        float $ration,
        string $class,
        Connection $connection,
        ChatNotification $chatNotification
    ) {
        parent::__construct($duration, $ration, $class);

        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
    }

    /**
     * @inheritdoc
     */
    public function create(Player $player): AbstractVote
    {
        return new NextMapVote(
            $player,
            $this->getVoteCode(),
            $this->duration,
            $this->ration,
            $this->connection,
            $this->chatNotification
        );
    }

    /**
     * @inheritdoc
     */
    public function getVoteCode(): string
    {
        return 'Exp_NextMap';
    }

    /**
     * @inheritdoc
     */
    public function getReplacementTypes()
    {
        return ['NextMap'];
    }
}