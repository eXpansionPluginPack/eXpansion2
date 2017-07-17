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

    /**
     * @param string $parameterLoginDescription
     */
    public function setParameterLoginDescription($parameterLoginDescription)
    {
        $this->parameterLoginDescription = $parameterLoginDescription;
    }

    /**
     * @param string $parameterReasonDescription
     */
    public function setParameterReasonDescription($parameterReasonDescription)
    {
        $this->parameterReasonDescription = $parameterReasonDescription;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $chatMessage
     */
    public function setChatMessage($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * @param string $functionName
     */
    public function setFunctionName($functionName)
    {
        $this->functionName = $functionName;
    }
}
