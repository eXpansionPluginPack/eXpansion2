<?php

namespace eXpansion\Bundle\CustomChat\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;


class CustomChat implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyChat, StatusAwarePluginInterface
{
    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var AdminGroups */
    protected $adminGroups;

    /** @var bool */
    protected $enabled = true;

    function __construct(Connection $connection, Console $console, AdminGroups $adminGroups)
    {
        $this->connection = $connection;
        $this->console = $console;
        $this->adminGroups = $adminGroups;
    }

    /**
     * Called when a player chats.
     *
     * @param Player $player
     * @param $text
     *
     * @return void
     */
    public function onPlayerChat(Player $player, $text)
    {
        $text = trim($text);
        $from = trim($player->getNickName());

        if ($player->getPlayerId() == 0) {
            return;
        }

        if ($player->getPlayerId() != 0 && substr($text, 0, 1) != "/" && $this->enabled) {
            $force = "";
            $nick = $player->getNickName();

            $nick = str_ireplace('$w', '', $nick);
            $nick = str_ireplace('$z', '$z$s', $nick);
            // fix for chat...
            $nick = str_replace('$<', '', $nick);
            $text = str_replace('$<', '', $text);

            try {
                $color = '$ff0';
                $separator = '';
                if ($this->adminGroups->isAdmin($player->getLogin())) {
                    $color = '$ff0';
                    $separator = '';
                }

                $this->connection->chatSendServerMessage(
                    '$fff$<'.$nick.'$z$s$> '.$separator.' '.$color.$force.$text,
                    null
                );
                $this->console->writeln('$ff0['.$from.'$ff0] '.$text);
            } catch (\Exception $e) {
                $this->console->writeln('$ff0 error while sending chat: $fff'.$e->getMessage());
            }
        } else {
            $this->connection->chatSendServerMessage('chat is disabled at the moment.', $player->getLogin());

        }

    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        if (!$status) {
            try {
                $this->connection->chatEnableManualRouting(false);
            } catch (\Exception $e) {
                $this->console->writeln('Error while disabling custom chat: $f00'.$e->getMessage());
            }
        }
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
        try {
            $this->connection->chatEnableManualRouting();
        } catch (\Exception $e) {
            $this->console->writeln('Error while enabling custom chat: $f00'.$e->getMessage());
        }
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
}
