<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ReasonUserCommand
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class ReasonUserCommand extends AbstractConnectionCommand
{
    /**
     * Description of the login parameter
     *
     * @var string
     */
    protected $parameterLoginDescription;

    /**
     * Description of the reason parameter.
     *
     * @var string
     */
    protected $parameterReasonDescription;

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
     * ReasonUserCommand constructor.
     *
     * @param $command
     * @param string $permission
     * @param array $aliases
     * @param AdminGroups $description
     * @param Connection $chatMessage
     * @param ChatNotification $functionName
     * @param PlayerStorage $parameterLoginDescription
     * @param LoggerInterface $parameterReasonDescription
     * @param AdminGroups $adminGroupsHelper
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     * @param LoggerInterface $logger
     * @param Time $timeHelper
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        $functionName,
        $parameterLoginDescription,
        $parameterReasonDescription,
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

        $this->description = 'expansion_admin_chat.' . strtolower($functionName) . '.description';
        $this->chatMessage = 'expansion_admin_chat.' . strtolower($functionName) . '.msg';
        $this->functionName = $functionName;
        $this->parameterLoginDescription = $parameterLoginDescription;
        $this->parameterReasonDescription = $parameterReasonDescription;
    }


    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::REQUIRED, $this->parameterLoginDescription)
        );
        $this->inputDefinition->addArgument(
            new InputArgument('reason', InputArgument::REQUIRED, $this->parameterReasonDescription)
        );
    }


    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $playerLogin = $input->getArgument('login');
        $reason = $input->getArgument('reason');
        $group = $this->getGroupLabel($login);

        $playerNickName = $this->playerStorage->getPlayerInfo($playerLogin)->getNickName();

        $this->chatNotification->sendMessage(
            $this->chatMessage,
            $this->isPublic ? null : $login,
            ['%adminLevel%' => $group, '%admin%' => $nickName, '%player%' => $playerNickName, "%reason%" => $reason]
        );

        $this->connection->{$this->functionName}($playerLogin, $reason);
    }
}
