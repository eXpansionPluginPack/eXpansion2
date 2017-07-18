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
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class AdminReturnCommand extends AbstractConnectionCommand
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
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

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
        $group = $this->adminGroupsHelper->getLoginUserGroups($login)->getName();

        $return = $this->connection->{$this->functionName}();

        $this->chatNotification->sendMessage(
            $this->chatMessage,
            $this->isPublic ? null : $login,
            ['%adminLevel%' => $group, '%admin%' => $nickName, '%return%' => $return]
        );


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
