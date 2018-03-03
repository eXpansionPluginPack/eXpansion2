<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\Core\Helpers\TMString;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Maniaplanet\DedicatedServer\Xmlrpc\Exception as DedicatedException;

/**
 * Class ReasonUserCommand
 *
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class TimeParameterCommand extends AbstractConnectionCommand
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
        $parameter = $this->timeHelper->textToTime($input->getArgument('parameter'));
        $group = $this->getGroupLabel($login);
        try {
            $this->factory->getConnection()->{$this->functionName}($parameter);
            $this->chatNotification->sendMessage(
                $this->chatMessage,
                $this->isPublic ? null : $login,
                ['%adminLevel%' => $group, '%admin%' => $nickName, "%parameter%" => $parameter]
            );

            $logMessage = $this->chatNotification->getMessage($this->chatMessage,
                ['%adminLevel%' => $group, '%admin%' => $nickName, "%parameter%" => $parameter], "en");
            $this->logger->info("[". $login. "] " . TMString::trimStyles($logMessage));

        }  catch (DedicatedException $e) {
            $this->logger->error("Error on admin command", ["exception" => $e]);
            $this->chatNotification->sendMessage("expansion_admin_chat.dedicatedexception", $login,
                ["%message%" => $e->getMessage()]);
        }
    }

    /**
     * @param string $parameterDescription
     */
    public function setParameterDescription($parameterDescription)
    {
        $this->parameterDescription = $parameterDescription;
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

    public function validate($login, $parameter)
    {
        return parent::validate($login, $parameter);
    }
}
