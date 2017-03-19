<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

class JoinLeaveMessages implements PlayerDataListenerInterface
{
    protected $connection;
    protected $console;
    private $enabled = true;

    function __construct(Connection $connection, Console $console)
    {
        $this->connection = $connection;
        $this->console = $console;
    }

    // @todo make this callback work!
    public function onRun()
    {
        $this->enabled = true;
    }

    public function onPlayerConnect(Player $player)
    {
        $msg = '$fffHello, ' . $player->getNickName() . '  $n$fff($888' . $player->getLogin() . '$fff)';
        if ($this->enabled)
            $this->connection->chatSendServerMessage($msg);
        // $this->console->writeln("Connect from " . $player->getPath() . "> " . $msg);
    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $msg = '$fffSee you, ' . $player->getNickName() . '  $n$fff($888' . $player->getLogin() . '$fff)';
        if ($this->enabled)
            $this->connection->chatSendServerMessage($msg);
        // $this->console->writeln("Disconnect from " . $player->getPath() . " > " . $msg);
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerInfoChanged() method.
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerAlliesChanged() method.
    }
}
