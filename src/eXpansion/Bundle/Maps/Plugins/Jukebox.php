<?php

namespace eXpansion\Bundle\Maps\Plugins;

use eXpansion\Bundle\Maps\Plugins\Gui\JukeboxWindowFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class Jukebox implements ListenerInterfaceMpScriptPodium, ListenerInterfaceMpLegacyMap
{
    /**
     * @var JukeboxService
     */
    private $jukeboxService;
    /**
     * @var Connection
     */
    private $connetion;
    /**
     * @var ChatNotification
     */
    private $chatNotification;
    /**
     * @var AdminGroups
     */
    private $adminGroups;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var JukeboxWindowFactory
     */
    private $jukeboxWindowFactory;

    /**
     * Jukebox constructor.
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param JukeboxService $jukeboxService
     * @param AdminGroups $adminGroups
     * @param PlayerStorage $playerStorage
     * @param MapStorage $mapStorage
     * @param JukeboxWindowFactory $jukeboxWindowFactory
     */
    public function __construct(
        Connection $connection,
        ChatNotification $chatNotification,
        JukeboxService $jukeboxService,
        AdminGroups $adminGroups,
        PlayerStorage $playerStorage,
        MapStorage $mapStorage,
        JukeboxWindowFactory $jukeboxWindowFactory
    ) {

        $this->jukeboxService = $jukeboxService;
        $this->connetion = $connection;
        $this->chatNotification = $chatNotification;
        $this->adminGroups = $adminGroups;
        $this->playerStorage = $playerStorage;
        $this->mapStorage = $mapStorage;
        $this->jukeboxWindowFactory = $jukeboxWindowFactory;
    }


    public function jukeboxCommand($login, $action)
    {
        switch ($action) {
            case "list":
            case "show":
                $this->view($login);
                break;
            case "drop":
                $this->drop($login);
                break;
            case "clear":
                $this->clear($login);
                break;
            default:
                $this->add($login, $action);
                break;
        }

    }

    public function view($login)
    {
        $this->jukeboxWindowFactory->setJukeboxPlugin($this);
        $this->jukeboxWindowFactory->updateMaps();
        $this->jukeboxWindowFactory->create($login);

    }

    public function add($login, $index)
    {
        if (is_numeric($index)) {
            $map = $this->mapStorage->getMapByIndex($index - 1);
            if ($map) {
                if ($this->jukeboxService->addMap($map, $login)) {
                    $player = $this->playerStorage->getPlayerInfo($login);
                    $length = count($this->jukeboxService->getMapQueue());
                    $this->chatNotification->sendMessage('expansion_jukebox.chat.addmap', null, [
                        "%mapname%" => $map->name,
                        "%nickname%" => $player->getNickName(),
                        "%length%" => $length,
                    ]);

                    return;
                } else {
                    $this->chatNotification->sendMessage('expansion_jukebox.chat.noadd', $login,
                        ["%mapname%" => $map->name]);

                    return;
                }
            }
        }
        $this->chatNotification->sendMessage('expansion_jukebox.chat.nomap', $login);
    }

    public function drop($login, $map = null)
    {
        if ($map === null) {
            $map = $this->jukeboxService->getMap($login);
        }

        if ($map) {
            if ($this->jukeboxService->removeMap($map->getMap(), $login)) {
                $length = count($this->jukeboxService->getMapQueue());
                $this->chatNotification->sendMessage('expansion_jukebox.chat.removemap', null, [
                    "%mapname%" => $map->getMap()->name,
                    "%nickname%" => $map->getPlayer()->getNickName(),
                    "%length%" => $length,
                ]);

                return;
            }
            $this->chatNotification->sendMessage('expansion_jukebox.chat.noremove', $login,
                ["%mapname%" => $map->getMap()->name]);

            return;
        }
        $this->chatNotification->sendMessage('expansion_jukebox.chat.nomap', $login);
    }

    public function clear($login)
    {
        if ($this->adminGroups->hasPermission($login, 'jukebox')) {
            $group = $this->adminGroups->getLoginUserGroups($login)->getName();
            $level = $this->adminGroups->getGroupLabel($group);
            $player = $this->playerStorage->getPlayerInfo($login);
            $this->jukeboxService->clearMapQueue();
            $this->chatNotification->sendMessage('expansion_jukebox.chat.clear', null,
                ["%adminlevel%" => $level, "%admin%" => $player->getNickName()]);
        } else {
            $this->chatNotification->sendMessage('expansion_jukebox.chat.nopermission', $login);
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
        // TODO: Implement setStatus() method.
    }

    /**
     * @param Map $map
     *
     * @return mixed
     */
    public function onBeginMap(Map $map)
    {
        $this->chatNotification->sendMessage('expansion_jukebox.chat.beginmap', null,
            ['%name%' => $map->name, '%author%' => $map->author]);
    }

    /**
     * @param Map $map
     *
     * @return mixed
     */
    public function onEndMap(Map $map)
    {
        $jbmap = $this->jukeboxService->getFirst();
        if ($jbmap) {
            $this->connetion->setNextMapIdent($jbmap->getMap()->uId);
            $this->jukeboxService->removeMap($jbmap->getMap(), null, true);
        }
    }

    /**
     * Callback sent when the "onPodiumStart" section start.
     *
     * @param int $time Server time when the callback was sent
     * @return mixed
     */
    public function onPodiumStart($time)
    {
        $jbMap = $this->jukeboxService->getFirst();
        if ($jbMap) {
            $length = count($this->jukeboxService->getMapQueue());
            $this->chatNotification->sendMessage('expansion_jukebox.chat.nextjbmap', null, [
                "%mapname%" => $jbMap->getMap()->name,
                "%mapauthor%" => $jbMap->getMap()->author,
                "%nickname%" => $jbMap->getPlayer()->getNickName(),
                "%length%" => $length,
            ]);
        } else {
            $map = $this->mapStorage->getNextMap();
            $this->chatNotification->sendMessage('expansion_jukebox.chat.nextmap', null, [
                "%name%" => $map->name,
                "%author%" => $map->author,
            ]);
        }
    }

    /**
     * Callback sent when the "onPodiumEnd" section end.
     *
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onPodiumEnd($time)
    {

    }
}
