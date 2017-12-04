<?php

namespace eXpansion\Bundle\VoteManager\Services\VoteFactories;

use eXpansion\Bundle\LocalRecords\Plugins\ChatNotification;
use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Structures\AbstractVote;
use eXpansion\Bundle\VoteManager\Structures\RestartMapVote;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;

/**
 * Class RestartMap
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\VoteManager\Services\VoteFactories
 */
class RestartMap extends AbstractFactory
{
    /** @var JukeboxService */
    protected $jukebox;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var ChatNotification */
    protected $chatNotification;

    public function __construct(
        int $duration,
        float $ration,
        string $class,
        JukeboxService $jukebox,
        MapStorage $mapStorage,
        ChatNotification $chatNotification
    ) {
        parent::__construct($duration, $ration, $class);

        $this->jukebox = $jukebox;
        $this->mapStorage = $mapStorage;
        $this->chatNotification = $chatNotification;
    }

    /**
     * @inheritdoc
     */
    public function create(Player $player): AbstractVote
    {
        return new RestartMapVote(
            $player,
            $this->getVoteCode(),
            $this->duration,
            $this->ration,
            $this->jukebox,
            $this->mapStorage,
            $this->chatNotification
        );
    }

    /**
     * @inheritdoc
     */
    public function getVoteCode(): string
    {
        return 'Exp_RestartMap';
    }

    /**
     * @inheritdoc
     */
    public function getReplacementTypes()
    {
        return ['RestartMap'];
    }
}