<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class Restart
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class Restart extends AbstractAdminChatCommand
{
    /** @var Connection  */
    protected $connection;

    /** @var ChatNotification  */
    protected $chatNotification;

    /** @var PlayerStorage  */
    protected $playerStorage;

    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        $parametersAsArray = true,
        AdminGroups $adminGroupsHelper,
        Connection $connection,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage
    )
    {
        parent::__construct($command, $permission, $aliases, $parametersAsArray, $adminGroupsHelper);

        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
    }


    /**
     * @param $login
     * @param $parameter
     *
     * @return void
     */
    public function execute($login, $parameter)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage('expansion_admin_chat.restart', null,['%nickname%' => $nickName]);
        $this->connection->restartMap();
    }
}
