<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Config\Model\TextConfig;
use eXpansion\Framework\Core\Helpers\FileSystem;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
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
class AutomaticMatchSettingsSave implements ListenerInterfaceMpLegacyMap, StatusAwarePluginInterface
{
    /** @var FileSystem */
    protected $fileSystem;

    /** @var Factory */
    protected $connectionFactory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var TextConfig */
    protected $fileName;

    /**
     * AutomaticServerSettingsSave constructor.
     *
     * @param FileSystem $fileSystem
     * @param Factory $connectionFactory
     * @param LoggerInterface $logger
     * @param TextConfig $fileName
     */
    public function __construct(FileSystem $fileSystem, Factory $connectionFactory, LoggerInterface $logger, TextConfig $fileName)
    {
        $this->fileSystem = $fileSystem;
        $this->connectionFactory = $connectionFactory;
        $this->logger = $logger;
        $this->fileName = $fileName;
    }


    public function saveFile()
    {
        if (!$this->fileName->get()) {
            return;
        }

        $path = 'MatchSettings/' . $this->fileName->get();

        try {
            $this->connectionFactory->getConnection()->saveMatchSettings($path);
        } catch (\Exception $e) {
            $this->logger->error('Failed to save match settings!', ['exception' => $e]);
        }
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