<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\Exception as DedicatedException;
use Psr\Log\LoggerInterface;

/**
 * Class Restart
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractConnectionCommand extends AbstractAdminChatCommand
{
    /** @var Factory */
    protected $factory;

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var LoggerInterface */
    protected $logger;

    /** @var Time */
    protected $timeHelper;

    /**
     * Send chat output to public chat
     * @var bool
     */
    protected $isPublic = true;

    /**
     * AbstractConnectionCommand constructor.
     *
     * @param $command
     * @param $permission
     * @param array $aliases
     * @param AdminGroups $adminGroupsHelper
     * @param Factory $factory
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     * @param LoggerInterface $logger
     * @param Time $timeHelper
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroupsHelper,
        Factory $factory,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        Time $timeHelper
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroupsHelper);

        $this->factory = $factory;
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
        $this->isPublic = (bool) $bool;
    }

    /**
     * Get admin group Label
     *
     * @param string $login
     * @return string
     */
    public function getGroupLabel($login)
    {
        $group = $this->adminGroupsHelper->getLoginUserGroups($login);

        $groupName = "Admin";
        if ($group) {
            if ($groupName) {
                $groupName = $this->adminGroupsHelper->getGroupLabel($group->getName());
            }
        }

        return $groupName;
    }

    /**
     * @return bool
     */
    public function getPublic()
    {
        return $this->isPublic;
    }
}
