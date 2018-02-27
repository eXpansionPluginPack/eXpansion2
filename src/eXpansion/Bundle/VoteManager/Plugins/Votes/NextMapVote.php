<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Votes;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class NextMapVote
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\VoteManager\Plugins\Votes
 */
class NextMapVote extends AbstractVotePlugin implements ListenerInterfaceMpScriptPodium
{
    /** @var Factory */
    protected $factory;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * NextMapVote constructor.
     *
     * @param PlayerStorage $playerStorage
     * @param Factory $factory
     * @param ChatNotification $chatNotification
     * @param int $duration
     * @param float $ratio
     */
    public function __construct(
        PlayerStorage $playerStorage,
        Factory $factory,
        ChatNotification $chatNotification,
        int $duration,
        float $ratio
    ) {
        parent::__construct($playerStorage, $duration, $ratio);

        $this->factory = $factory;
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
    protected function executeVotePassed()
    {
        $this->factory->getConnection()->nextMap(false);
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

    /**
     * Callback sent when the "onPodiumStart" section start.
     *
     * @param int $time Server time when the callback was sent
     * @return void
     */
    public function onPodiumStart($time)
    {
        //nothing
    }

    /**
     * Callback sent when the "onPodiumEnd" section end.
     *
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onPodiumEnd($time)
    {
        $this->cancel();
    }
}
