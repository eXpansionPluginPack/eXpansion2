<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Psr\Log\LoggerInterface;

/**
 * Class Analytics
 *
 * @author    de Cramer Oliver<olverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Core\Plugins
 */
class Analytics implements ListenerInterfaceExpTimer, StatusAwarePluginInterface
{
    /** @var Http */
    protected $http;

    /** @var GameDataStorage */
    protected $gameData;

    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var LoggerInterface */
    protected $logger;

    /** @var string  */
    protected $url;

    /** @var int */
    protected $pingInterval;

    /** @var int */
    protected $retryInterval;

    /** @var bool Is a call in progress. */
    protected $operationInProgress = false;

    /** @var string */
    protected $key = null;

    /** @var int */
    protected $lastPing;

    /**
     * Analytics constructor.
     *
     * @param Http $http
     * @param GameDataStorage $gameData
     * @param PlayerStorage $playerStorage
     * @param LoggerInterface $logger
     * @param string $url
     * @param int $pingInterval
     * @param int $retryInterval
     */
    public function __construct(
        Http $http,
        GameDataStorage $gameData,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        string $url,
        int $pingInterval,
        int $retryInterval
    ) {
        $this->http = $http;
        $this->gameData = $gameData;
        $this->playerStorage = $playerStorage;
        $this->logger = $logger;
        $this->url = $url;
        $this->pingInterval = $pingInterval;
        $this->retryInterval = $retryInterval;
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
        if ($status && !$this->operationInProgress) {
            $this->handshake();
        }
    }

    /**
     * Handshake with analytics tool!
     */
    protected function handshake()
    {
        $key =        &$this->key;
        $lastPing =   &$this->lastPing;
        $that = $this;
        $logger = $this->logger;

        $query = http_build_query(
            ['page' => 'handshake', 'server-login' => $this->gameData->getSystemInfo()->serverLogin]
        );
        $url = $this->url . '?' . $query;

        $lastPing = time();
        $this->logger->debug("[eXpansion analytics]Starting handshake");


        $this->http->get(
            $url,
            function (HttpResult $result) use (&$key, &$lastPing, $logger, $that) {
                $key = null;

                if ($result->getHttpCode() != '200') {
                    $logger->debug('[eXpansion analytics]Handshake failed', ['http_code' => $result->getHttpCode()]);
                    return;
                }

                $json = json_decode($result->getResponse());
                if (isset($json->key) && !empty($json->key)) {
                    $logger->debug('[eXpansion analytics]Handshake successfull', ['key' => $json->key]);

                    $key = $json->key;
                    $that->ping();

                    // allow ping just after handshake.
                    $lastPing = 0;
                }
            }
        );
    }

    /**
     * Ping the analytics server with proper information.
     */
    public function ping()
    {
        if (!$this->key || $this->operationInProgress || (time() - $this->lastPing) < $this->pingInterval) {
            // Attempt a new handshake.
            if (is_null($this->key) && (time() - $this->lastPing) > $this->retryInterval) {
                $this->lastPing = time();
                $this->handshake();
            }

            return;
        }

        $data = $this->getBasePingData();
        $data['page'] = 'ping';

        $query = http_build_query(
            $data
        );
        $url = $this->url . '?' . $query;

        $this->operationInProgress = true;
        $this->lastPing = time();
        $operationInProgress = &$this->operationInProgress;
        $key = &$this->key;
        $logger = $this->logger;

        $logger->debug('[eXpansion analytics]Starting ping');
        $this->http->get(
            $url,
            function (HttpResult $result) use (&$operationInProgress, &$key, $logger) {
                if ($result->getHttpCode() == '200') {
                    $operationInProgress = false;
                    $logger->debug('[eXpansion analytics]Ping successfull');
                } else {
                    $logger->debug('[eXpansion analytics]Ping failed', ['http_code' => $result->getHttpCode(), 'result' => $result->getResponse()]);
                    $key = null;
                }
            }
        );
    }

    /**
     * Get base data for pinging the server.
     *
     * @return array
     */
    protected function getBasePingData()
    {
        return [
            'key' => $this->key,
            'nbPlayers' => count($this->playerStorage->getOnline()),
            'country' => $this->gameData->getServerCountry(),
            'version' => Application::EXPANSION_VERSION,
            'php_version' => $this->gameData->getServerCleanPhpVersion(),
            'php_version_short' => $this->gameData->getServerMajorPhpVersion(),
            'mysql_version' => 'unknown',
            'memory' => memory_get_usage(),
            'memory_peak' => memory_get_peak_usage(),
            'build' => '',
            'title' => str_replace('@','_by_', $this->gameData->getVersion()->titleId),
            'game' => $this->gameData->getTitle(),
            'mode' => strtolower($this->gameData->getGameInfos()->scriptName),
            'serverOs' => $this->gameData->getServerOs(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
        // Nothing.
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        // Nothing.
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
        $this->ping();
    }
}