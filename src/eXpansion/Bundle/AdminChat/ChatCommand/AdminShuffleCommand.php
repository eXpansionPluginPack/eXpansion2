<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ReasonUserCommand
 *
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class AdminShuffleCommand extends AbstractAdminChatCommand
{
    /** @var ChatNotification */
    private $chatNotification;

    /** @var PlayerStorage */
    private $playerStorage;

    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AdminCommand constructor.
     *
     * @param                  $command
     * @param string $permission
     * @param array $aliases
     * @param AdminGroups $adminGroupsHelper
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     * @param LoggerInterface $logger
     * @param Connection $connection
     * @param MapStorage $mapStorage
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroupsHelper,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        Connection $connection,
        MapStorage $mapStorage
    ) {
        parent::__construct(
            $command,
            $permission,
            $aliases = [],
            $adminGroupsHelper
        );

        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
        $this->mapStorage = $mapStorage;
        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->connection = $connection;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $allMaps = [];
        foreach ($this->mapStorage->getMaps() as $map) {
            $maps[] = $map->fileName;
            $allMaps[] = $map->fileName;
            if (count($maps) > 250) {
                $this->connection->removeMapList($maps, true);
                $maps = [];
            }
        }
        // for remaining maps, which didn't fit to 200
        $this->connection->removeMapList($maps, true);
        $this->connection->executeMulticall();

        shuffle($allMaps);

        $maps = [];
        foreach ($allMaps as $x => $mapFile) {
            $maps[] = $mapFile;
            if (count($maps) > 250) {
                $this->connection->insertMapList($maps, true);
                $maps = [];
            }
        }
        // for remaining maps, which didn't fit to 200
        $this->connection->insertMapList($maps, true);
        $this->connection->executeMulticall();

        $level = $this->adminGroupsHelper->getLoginGroupLabel($login);
        $admin = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage('expansion_admin_chat.shuffle.msg', null,
            ["%adminLevel%" => $level, "%admin%" => $admin]);

        $logMessage = $this->chatNotification->getMessage('expansion_admin_chat.shuffle.msg',
            ["%adminLevel%" => $level, "%admin%" => $admin], "en");
        $this->logger->info("[". $login. "] " . TMString::trimStyles($logMessage));

    }
}
