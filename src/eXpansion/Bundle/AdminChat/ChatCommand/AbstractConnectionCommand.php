<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class Restart
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractConnectionCommand extends AbstractAdminChatCommand
{
    /** @var Connection */
    protected $connection;

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var Logger */
    protected $logger;

    /** @var Time */
    protected $timeHelper;

    /**
     * Send chat output to public chat
     * @var bool
     */
    protected $isPublic = true;

    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroupsHelper,
        Connection $connection,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        Time $timeHelper
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroupsHelper);

        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
        $this->logger = $logger;
        $this->timeHelper = $timeHelper;
    }

    /**
     * @param bool $bool chat output visibility
     */
    public function setPublic($bool)
    {
        $this->isPublic = (bool)$bool;
    }

}
