<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\VoteDataProvider;

/**
 * Interface for plugins using the VoteDataProvider.
 *
 * @see VoteDataProvider
 * @author reaby
 */
interface ListenerInterfaceMpLegacyVote
{
    /**
     * When a new vote is addressed
     *
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteNew(Player $player, $cmdName, $cmdValue);

    /**
     * When vote gets cancelled
     *
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteCancelled(Player $player, $cmdName, $cmdValue);

    /**
     * When vote Passes
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVotePassed(Player $player, $cmdName, $cmdValue);

    /**
     * When vote Fails
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteFailed(Player $player, $cmdName, $cmdValue);

    /**
     * When vote Fails
     * @param Player $player
     * @param Vote   $vote
     * @return void
     */
    public function onVoteYes(Player $player, $vote);

    /**
     * When vote Fails
     * @param Player $player
     * @param Vote   $vote
     * @return void
     */
    public function onVoteNo(Player $player, $vote);


}
