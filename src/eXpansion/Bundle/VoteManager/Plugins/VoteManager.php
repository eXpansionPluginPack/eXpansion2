<?php

namespace eXpansion\Bundle\VoteManager\Plugins;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\UpdateVoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\VoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyVote;
use Maniaplanet\DedicatedServer\Connection;

class VoteManager implements ListenerInterfaceMpLegacyVote, ListenerInterfaceExpTimer
{

    const YES = "yes";
    const NO = "no";

    /**
     * @var Console
     */
    private $console;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /** @var Vote|null */
    public static $currentVote = null;

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
     * @var JukeboxService
     */
    private $jukebox;
    /**
     * @var MapStorage
     */
    private $mapStorage;

    /** @var array */
    private $voteStarted = [];
    /**
     * @var VoteService
     */
    private $voteService;

    /**
     * VoteManager constructor.
     * @param Console $console
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param VoteWidgetFactory $voteWidgetFactory
     * @param UpdateVoteWidgetFactory $updateVoteWidgetFactory
     * @param Group $players
     * @param JukeboxService $jukebox
     * @param MapStorage $mapStorage
     * @param VoteService $voteService
     */
    public function __construct(
        Console $console,
        Connection $connection,
        ChatNotification $chatNotification,
        VoteWidgetFactory $voteWidgetFactory,
        UpdateVoteWidgetFactory $updateVoteWidgetFactory,
        Group $players,
        JukeboxService $jukebox,
        MapStorage $mapStorage,
        VoteService $voteService
    ) {
        $this->console = $console;
        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
        $this->voteWidgetFactory = $voteWidgetFactory;
        $this->players = $players;
        $this->jukebox = $jukebox;

        $this->mapStorage = $mapStorage;
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
            $text = "Unknown Vote";
            switch ($cmdValue->getType()) {
                case "Exp_RestartMap":
                    $text = "expansion_votemanager.gui.vote_widget.restart";
                    break;
                case "Exp_NextMap":
                    $text = "expansion_votemanager.gui.vote_widget.skip";
                    break;
            }

            $this->updateVoteWidgetFactory->create($this->players);
            $this->voteWidgetFactory->create($this->players);
            $this->voteWidgetFactory->setMessage($text);
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
            switch ($cmdName) {
                case "Exp_RestartMap":
                    $this->chatNotification->sendMessage("|info| Vote passed. Map will replay.");
                    $this->jukebox->addMap($this->mapStorage->getCurrentMap(), $cmdValue->getPlayer()->getLogin(),
                        true);
                    break;
                case "Exp_NextMap":
                    $this->connection->nextMap(false);
                    $this->chatNotification->sendMessage("|info| Vote passed. Skipping map!");
                    break;
            }
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

    public function onPreLoop()
    {
        // TODO: Implement onPreLoop() method.
    }

    public function onPostLoop()
    {
        // TODO: Implement onPostLoop() method.
    }

    public function onEverySecond()
    {
        if ($this->voteService->getCurrentVote() instanceof Vote) {
            $this->updateVoteWidgetFactory->update($this->players);
        }
    }
}

