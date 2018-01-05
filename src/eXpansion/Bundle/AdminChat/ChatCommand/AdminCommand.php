<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\Exception as DedicatedException;
use Maniaplanet\DedicatedServer\Xmlrpc\FaultException;
use Maniaplanet\DedicatedServer\Xmlrpc\UnknownPlayerException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class ReasonUserCommand
 *
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class AdminCommand extends AbstractConnectionCommand
{
    /**
     * Description of the command.
     *
     * @var string
     */
    protected $description;

    /**
     * Message to display in chat.
     *
     * @var string
     */
    protected $chatMessage;

    /**
     * Name of the dedicated function to call.
     *
     * @var string
     */
    protected $functionName;

    /**
     * AdminCommand constructor.
     *
     * @param                  $command
     * @param string           $permission
     * @param array            $aliases
     * @param string           $functionName
     * @param AdminGroups      $adminGroupsHelper
     * @param Connection       $connection
     * @param ChatNotification $chatNotification
     * @param PlayerStorage    $playerStorage
     * @param LoggerInterface  $logger
     * @param Time             $timeHelper
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        $functionName,
        AdminGroups $adminGroupsHelper,
        Connection $connection,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        Time $timeHelper
    ) {
        parent::__construct(
            $command,
            $permission,
            $aliases,
            $adminGroupsHelper,
            $connection,
            $chatNotification,
            $playerStorage,
            $logger,
            $timeHelper
        );

        $this->description = 'expansion_admin_chat.'.strtolower($functionName).'.description';
        $this->chatMessage = 'expansion_admin_chat.'.strtolower($functionName).'.msg';
        $this->functionName = $functionName;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $group = $this->getGroupLabel($login);
        try {
            $this->connection->{$this->functionName}();
            $this->chatNotification->sendMessage(
                $this->chatMessage,
                $this->isPublic ? null : $login,
                ['%adminLevel%' => $group, '%admin%' => $nickName]
            );
        } catch (DedicatedException $e) {
            $this->logger->error("Error on admin command", ["exception" => $e]);
            $this->chatNotification->sendMessage("expansion_admin_chat.dedicatedexception", $login,
                ["%message%" => $e->getMessage()]);
        }

    }
}
