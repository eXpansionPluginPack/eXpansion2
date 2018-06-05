<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Config\Model\TextConfig;
use eXpansion\Framework\Core\Helpers\FileSystem;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Structures\Map;
use Psr\Log\LoggerInterface;

/**
 * Class AutomaticServerSettingsSave
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\Core\Plugins
 */
class AutomaticServerSettingsSave implements ListenerInterfaceMpLegacyMap, StatusAwarePluginInterface
{
    /** @var FileSystem */
    protected $fileSystem;

    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var LoggerInterface */
    protected $logger;

    /** @var TextConfig */
    protected $fileName;

    /**
     * AutomaticServerSettingsSave constructor.
     *
     * @param FileSystem $fileSystem
     * @param GameDataStorage $gameDataStorage
     * @param $fileName
     */
    public function __construct(
        FileSystem $fileSystem,
        GameDataStorage $gameDataStorage,
        LoggerInterface $logger,
        $fileName
    ) {
        $this->fileSystem = $fileSystem;
        $this->gameDataStorage = $gameDataStorage;
        $this->logger = $logger;
        $this->fileName = $fileName;
    }



    public function saveFile()
    {
        if (!$this->fileName->get()) {
            return;
        }

        $path = 'Config/' . $this->fileName->get();
        $fileSystem = $this->fileSystem->getUserData();

        if (!$fileSystem->has($path)) {
            $this->logger->warning("Can't find file '$path'! Won't save server settings!");
            return;
        }

        /** @var \SimpleXMLElement */
        $oldXml = simplexml_load_string($fileSystem->read($path));
        if ($oldXml === false) {
            $this->logger->error("Invalid xml in source file '$path'! Won't save server settings!");
            return;
        }

        $adapter = array("name" => "name",
            "password" => "password",
            "comment" => "comment",
            "passwordForSpectator" => "password_spectator",
            "hideServer" => "hide_server",
            "nextMaxPlayers" => "max_players",
            "nextMaxSpectatos" => "max_spectators",
            "isP2PUpload" => "enable_p2p_upload",
            "isP2PDownload" => "enable_p2p_download",
            "nextLadderMode" => "ladder_mode",
            "nextCallVoteTimeOut" => "callvote_timeout",
            "callVoteRatio" => "callvote_ratio",
            "allowMapDownload" => "allow_map_download",
            "autoSaveReplays" => "autosave_replays",
            "autoSaveValidationReplays" => "autosave_validation_replays",
            "refereePassword" => "referee_password",
            "refereeMode" => "referee_validation_mode",
            "disableHorns" => "disable_horns",
            "clientInputsMaxLatency" => "clientinputs_maxlatency",
            "keepPlayerSlots" => "keep_player_slots",
        );

        $new = $this->gameDataStorage->getServerOptions();
        foreach ($this->gameDataStorage->getServerOptions() as $key => $value) {
            //           $search = $key;
            if (array_key_exists($key, $adapter)) {
                $search = $adapter[$key];
            } else {
                continue;
            }
            $out = $new->{$key};
            if (is_bool($value)) {
                $out = "False";
                if ($value) {
                    $out = "True";
                }
            }
            $oldXml->server_options->{$search}[0] = $out;
        }
        $this->logger->info('Saving server settings to : ' . $path);
        $xml = $oldXml->asXML();

        $fileSystem->update($path, $xml);
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {
        $this->saveFile();
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map)
    {
        // Nothing to do.
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
        $this->saveFile();
    }
}