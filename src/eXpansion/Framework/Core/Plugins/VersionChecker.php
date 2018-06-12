<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Helpers\Version;
use eXpansion\Framework\Core\Services\Console;
use Psr\Log\LoggerInterface;
use Symfony\Component\Intl\Util\IcuVersion;

/**
 * Class VersionChecker
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\Core\Plugins
 */
class VersionChecker  implements ListenerInterfaceExpTimer
{
    /** @var Version */
    protected $version;

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var LoggerInterface */
    protected $logger;

    /** @var Console */
    protected $console;

    /** @var Http */
    protected $http;

    /** @var string */
    protected $versionUrl;

    /** @var int */
    protected $checkInterval = 4 * 3600;

    /** @var int */
    protected $lastCheck = 0;

    /**
     * VersionChecker constructor.
     *
     * @param Version $version
     * @param ChatNotification $chatNotification
     * @param LoggerInterface $logger
     * @param Console $console
     * @param Http $http
     * @param string $versionUrl
     * @param int $checkInterval
     */
    public function __construct(
        Version $version,
        ChatNotification $chatNotification,
        LoggerInterface $logger,
        Console $console,
        Http $http,
        string $versionUrl,
        $checkInterval = null
    ) {
        $this->version = $version;
        $this->chatNotification = $chatNotification;
        $this->logger = $logger;
        $this->console = $console;
        $this->http = $http;
        $this->versionUrl = $versionUrl;
        if (!is_null($checkInterval)) {
            $this->checkInterval = $checkInterval;
        }
    }


    public function onHttpDone(HttpResult $result)
    {
        if ($result->getHttpCode() != 200) {
            $this->console->getSfStyleOutput()->warning("Can't connect to eXpansion api to fetch version information");
            $this->logger->warning(
                "Can't connect to eXpansion api to fetch version information",
                [
                    'url' => $this->versionUrl,
                    'response_code' => $result->getHttpCode(),
                    'response_msg' => $result->getResponse(),
                ]
            );

            return;
        }

        // TODO do proper notifications.

        $result = json_decode($result->getResponse(), true);
        $stableV = null;
        $preV = null;
        $isLastVersion = false;

        if (isset($result['stable']) && $result['stable']) {
            $stableV = $result['stable']['name'];
            $isLastVersion = IcuVersion::compare($result['stable']['name'], $this->version->getExpansionVersion(), '==');

            if ($isLastVersion) {
                // No questions nothing to do.
                return;
            }
        }

        if (isset($result['pre']) && $result['pre']) {
            $preV = $result['pre']['name'];
            $isLastPreRelease = IcuVersion::compare($result['pre']['name'], $this->version->getExpansionVersion(), '<=');

            if ($isLastPreRelease && $stableV) {
                // Need to check if last prerelease is not older then the last stable release.
                if(IcuVersion::compare($stableV, $preV, '>')) {
                    $this->chatNotification->sendMessage("There is an update for expansion available : $stableV");
                }
                return;
            }

            if (!$isLastPreRelease) {
                if (!$isLastVersion && $stableV) {
                    $this->chatNotification->sendMessage("There is an update for expansion available : $stableV");
                } else {
                    $this->chatNotification->sendMessage("There is an new pre-release for expansion available : $preV");
                }
            }
        }
    }

    public function onEverySecond()
    {
        if ($this->lastCheck + $this->checkInterval < time()) {
            $this->lastCheck = time();
            $this->http->get($this->versionUrl, [$this, 'onHttpDone']);
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