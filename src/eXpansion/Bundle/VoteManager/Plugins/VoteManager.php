<?php

namespace eXpansion\Bundle\VoteManager\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyVote;
use Maniaplanet\DedicatedServer\Connection;

class VoteManager implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyVote
{

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

    public function __construct(Console $console, Connection $connection, ChatNotification $chatNotification)
    {
        $this->console = $console;
        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // TODO: Implement onApplicationInit() method.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        // TODO: Implement onApplicationReady() method.
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // TODO: Implement onApplicationStop() method.
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
        switch ($cmdName) {
            case "NextMap":
                $this->connection->cancelVote();
                $this->chatNotification->sendMessage("will do new nextmap custom vote!");
                break;
            case "RestartMap":
                $this->connection->cancelVote();
                $this->chatNotification->sendMessage("will do new restart custom vote!");
                break;
            case "Kick":
                $this->connection->cancelVote();
                $this->chatNotification->sendMessage("will do new kick custom vote!");
                break;
        }
        $this->console->writeln(__FUNCTION__." -> ".$player->getNickName()." $cmdName => $cmdValue");
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
        $this->console->writeln(__FUNCTION__." -> ".$player->getNickName()." $cmdName => $cmdValue");
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
        $this->console->writeln(__FUNCTION__." -> ".$player->getNickName()." $cmdName => $cmdValue");
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
        $this->console->writeln(__FUNCTION__." -> ".$player->getNickName()." $cmdName => $cmdValue");
    }
}
