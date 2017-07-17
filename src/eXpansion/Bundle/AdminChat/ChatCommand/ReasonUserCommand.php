<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ReasonUserCommand
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class ReasonUserCommand extends AbstractConnectionCommand
{
    protected $parameterLoginDescription;

    protected $parameterReasonDescription;

    protected $description;

    protected $chatMessage;

    protected $functionName;

    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroupsHelper,
        Connection $connection,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        $parameterLoginDescription,
        $parameterReasonDescription,
        $description,
        $chatMessage,
        $functionName
    ) {
        parent::__construct(
            $command,
            $permission,
            $aliases,
            $adminGroupsHelper,
            $connection,
            $chatNotification,
            $playerStorage,
            $logger
        );

        $this->parameterLoginDescription = $parameterLoginDescription;
        $this->parameterReasonDescription = $parameterReasonDescription;
        $this->description = $description;
        $this->chatMessage = $chatMessage;
        $this->functionName = $functionName;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $playerLogin = $input->getArgument('login');
        $reason = $input->getArgument('reason');

        $playerNickName = $this->playerStorage->getPlayerInfo($playerLogin)->getNickName();

        $this->chatNotification->sendMessage(
            $this->chatMessage,
            null,
            ['%admin%' => $nickName, '%player%' => $playerNickName, "%reason%" => $reason]
        );

        $this->connection->{$this->functionName}($playerLogin, $reason);
    }
}
