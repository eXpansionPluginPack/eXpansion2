<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\Exception as DedicatedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ReasonUserCommand
 *
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class OneParameterCommand extends AbstractConnectionCommand
{
    /**
     * Description of the parameter
     *
     * @var string
     */
    protected $parameterDescription;

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
     * OneParameterCommand constructor.
     *
     * @param                  $command
     * @param string           $permission
     * @param array            $aliases
     * @param string           $functionName
     * @param string           $parameterDescription
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
        $parameterDescription,
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
        $this->functionName = (string)$functionName;
        $this->parameterDescription = (string)$parameterDescription;
    }


    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('parameter', InputArgument::REQUIRED, $this->parameterDescription)
        );
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $parameter = $input->getArgument('parameter');
        $group = $this->getGroupLabel($login);
        try {
            $this->connection->{$this->functionName}($parameter);
            $this->chatNotification->sendMessage(
                $this->chatMessage,
                $this->isPublic ? null : $login,
                ['%adminLevel%' => $group, '%admin%' => $nickName, "%parameter%" => $parameter]
            );
        } catch (DedicatedException $e) {
            $this->logger->error("Error on admin command", ["exception" => $e]);
            $this->chatNotification->sendMessage("expansion_admin_chat.dedicatedexception", $login,
                ["%message%" => $e->getMessage()]);
        }

    }
}
