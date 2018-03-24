<?php

namespace eXpansion\Bundle\VoteManager\Plugins;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\UpdateVoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\VoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Plugins\Votes\AbstractVotePlugin;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyVote;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use Maniaplanet\DedicatedServer\Connection;

class VoteManager implements ListenerInterfaceMpLegacyVote, ListenerInterfaceExpTimer
{
    const YES = "yes";
    const NO = "no";

    /**
     * @var VoteWidgetFactory
     */
    private $voteWidgetFactory;

    /**
     * @var UpdateVoteWidgetFactory
     */
    private $updateVoteWidgetFactory;

    /**
     * @var Group
     */
    private $players;

    /**
     * @var VoteService
     */
    private $voteService;

    /**
     * VoteManager constructor.
     *
     * @param VoteWidgetFactory $voteWidgetFactory
     * @param UpdateVoteWidgetFactory $updateVoteWidgetFactory
     * @param Group $players
     * @param VoteService $voteService
     */
    public function __construct(
        VoteWidgetFactory $voteWidgetFactory,
        UpdateVoteWidgetFactory $updateVoteWidgetFactory,
        Group $players,
        VoteService $voteService
    ) {
        $this->voteWidgetFactory = $voteWidgetFactory;
        $this->players = $players;
        $this->voteService = $voteService;
        $this->updateVoteWidgetFactory = $updateVoteWidgetFactory;
    }

    /**
     * When a new vote is addressed
     *
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteNew(Player $player, $cmdName, $cmdValue)
    {
        if ($cmdValue instanceof Vote) {
            $this->updateVoteWidgetFactory->create($this->players);
            $this->voteWidgetFactory->create($this->players);
        } else {
            $this->voteService->startVote($player, $cmdName, ['value' => $cmdValue]);
        }
    }

    /**
     * When vote gets cancelled
     *
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteCancelled(Player $player, $cmdName, $cmdValue)
    {

        if ($cmdValue instanceof Vote) {
            $this->voteWidgetFactory->destroy($this->players);
            $this->updateVoteWidgetFactory->destroy($this->players);
        } else {
            $this->voteService->cancel();
        }
    }

    /**
     * When vote Passes
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVotePassed(Player $player, $cmdName, $cmdValue)
    {
        if ($cmdValue instanceof Vote) {
            $this->voteWidgetFactory->destroy($this->players);
            $this->updateVoteWidgetFactory->destroy($this->players);
        }
    }

    /**
     * When vote Fails
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteFailed(Player $player, $cmdName, $cmdValue)
    {
        if ($cmdValue instanceof Vote) {
            $this->voteWidgetFactory->destroy($this->players);
            $this->updateVoteWidgetFactory->destroy($this->players);
        }
    }

    public function onEverySecond()
    {
        if ($this->voteService->getCurrentVote() instanceof AbstractVotePlugin) {
            $this->voteService->update();
        }
    }

    /**
     * When vote Fails
     * @param Player $player
     * @param Vote   $vote
     * @return void
     */
    public function onVoteYes(Player $player, $vote)
    {
        if ($this->voteService->getCurrentVote() instanceof AbstractVotePlugin) {
            $this->updateVoteWidgetFactory->updateVote($vote);
        }
    }

    /**
     * When vote Fails
     * @param Player $player
     * @param Vote   $vote
     * @return void
     */
    public function onVoteNo(Player $player, $vote)
    {
        if ($this->voteService->getCurrentVote() instanceof AbstractVotePlugin) {
            $this->updateVoteWidgetFactory->updateVote($vote);
        }
    }

    public function onPreLoop()
    {
        // Nothing
    }

    public function onPostLoop()
    {
        // Nothing
    }

}

