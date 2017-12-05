<?php

namespace eXpansion\Bundle\CustomChat\Plugins;


use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use Maniaplanet\DedicatedServer\Connection;


class CustomChat implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyChat
{
    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var AdminGroups */
    protected $adminGroups;

    /** @var bool */
    protected $enabled = true;
    /**
     * @var ChatNotification
     */
    private $chatNotification;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * CustomChat constructor.
     * @param Connection $connection
     * @param Console $console
     * @param AdminGroups $adminGroups
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     */
    function __construct(
        Connection $connection,
        Console $console,
        AdminGroups $adminGroups,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->adminGroups = $adminGroups;
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
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

        if ($player->getPlayerId() != 0 && substr($text, 0, 1) != "/") {
            $nick = $player->getNickName();

            $nick = str_ireplace('$w', '', $nick);
            $nick = str_ireplace('$z', '$z$s', $nick);

            // fix for chat...
            $nick = str_replace('$<', '', $nick);
            $nick = str_replace('$>', '', $nick);

            $text = str_replace('$<', '', $text);
            $matches = [];

            $enabled = $this->enabled;
            try {
                $color = '$ff0';
                $separator = '';
                if ($this->adminGroups->isAdmin($player->getLogin())) {
                    $color = '$ff0';
                    $separator = '';
                    $enabled = true;
                }

                if ($enabled) {
                    $matchFound = false;
                    $matchLogin = [];

                    if (preg_match_all("/(\@(?P<login>[\w-._]+)\s?)/", $text, $matches)) {
                        $group = [];

                        foreach ($this->playerStorage->getOnline() as $player) {
                            foreach ($matches['login'] as $login) {
                                if ($player->getLogin() == $login) {
                                    $matchFound = true;
                                    $matchLogin[$player->getLogin()] = $player->getLogin();
                                }
                            }
                            $group[$player->getLogin()] = $player->getLogin();
                        }

                        $diff = array_diff($group, $matchLogin);

                        if ($matchFound) {
                            $this->connection->chatSendServerMessage(
                                '$fff$<'.$nick.'$>$z$s$fff '.$separator.' $f9f'.$text,
                                $matchLogin
                            );

                            if (count($diff) > 0) {
                                $this->connection->chatSendServerMessage(
                                    '$fff$<'.$nick.'$>$z$s$fff '.$separator.' '.$color.$text,
                                    $diff
                                );
                            }
                            $this->console->writeln('$ff0['.$from.'$ff0] '.$text);

                            return;
                        } else {
                            $this->connection->chatSendServerMessage(
                                '$fff$<'.$nick.'$>$z$s$fff '.$separator.' '.$color.$text,
                                null
                            );
                            $this->console->writeln('$ff0['.$from.'$ff0] '.$text);

                            return;
                        }
                    } else {
                        $this->connection->chatSendServerMessage(
                            '$fff$<'.$nick.'$>$z$s$fff '.$separator.' '.$color.$text,
                            null
                        );
                        $this->console->writeln('$ff0['.$from.'$ff0] '.$text);
                    }
                } else {
                    $this->console->writeln('$333['.$from.'$333] '.$text);
                    $this->chatNotification->sendMessage('expansion_customchat.chat.disabledstate',
                        $player->getLogin());
                }

            } catch (\Exception $e) {
                $this->console->writeln('$ff0 error while sending chat: $fff'.$e->getMessage());
            }
        }

    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->enabled = $status;
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
