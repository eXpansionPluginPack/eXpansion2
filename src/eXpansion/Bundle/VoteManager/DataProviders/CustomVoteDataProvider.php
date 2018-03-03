<?php

namespace eXpansion\Bundle\VoteManager\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;

class CustomVoteDataProvider extends AbstractDataProvider
{

    public function onVoteNew(Player $player, $cmdName, $cmdValue)
    {
        $this->dispatch(__FUNCTION__, [$player, $cmdName, $cmdValue]);
    }

    public function onVoteCancelled(Player $player, $cmdName, $cmdValue)
    {
        $this->dispatch(__FUNCTION__, [$player, $cmdName, $cmdValue]);
    }

    public function onVotePassed(Player $player, $cmdName, $cmdValue)
    {
        $this->dispatch(__FUNCTION__, [$player, $cmdName, $cmdValue]);
    }

    public function onVoteFailed(Player $player, $cmdName, $cmdValue)
    {
        $this->dispatch(__FUNCTION__, [$player, $cmdName, $cmdValue]);
    }

    public function onVoteYes(Player $player, $vote)
    {
        $this->dispatch(__FUNCTION__, [$player, $vote]);
    }

    public function onVoteNo(Player $player, $vote)
    {
        $this->dispatch(__FUNCTION__, [$player, $vote]);
    }


}
